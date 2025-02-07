<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Facades\Log;

class CampaignController extends Controller
{

    
    public function store(Request $request)
    {
        // Adicionando log para início da função
        Log::info('Iniciando o processo de criação de campanha.', ['user_id' => auth()->id()]);
    
        // Validação dos dados
        try {
            Log::info('Validando dados do formulário.');
            
            $request->validate([
                'name' => 'required|string|max:255',
                'domain' => 'required|exists:domains,id', // Certifica que o domínio existe
                'language' => 'required|string|max:255',
                'traffic_source' => 'required|string|max:255',
                'safe_page' => 'required|mimes:zip,html|max:20480',
                'method_safe' => 'required|string|max:255',
                'method_offer' => 'required|string|max:255',
                'offer_pages' => 'required|array',
                'offer_pages.*' => 'required|url',
                'target_countries' => 'required|array',
                'target_countries.*' => 'string|max:2', // Limitar a 2 caracteres para códigos de país (ISO)
                'target_devices' => 'required|array',
                'target_devices.*' => 'string|max:255', // Limitar os dispositivos a strings
            ]);
    
            Log::info('Validação concluída com sucesso.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erro na validação do formulário.', ['errors' => $e->errors(), $request->all()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    
        // Gerar hash para a campanha
        $hash = Str::random(10);
        Log::info('Gerando hash para a campanha: ' . $hash);

        // Manipulação do arquivo enviado
        $file = $request->file('safe_page');
    
        try {
            if ($file->getClientOriginalExtension() === 'zip') {
                Log::info('Arquivo ZIP detectado. Extraindo e armazenando.');
                // Chama a função para extrair e salvar o arquivo zip
                $directoryName = 'campaign_' . $hash;
                $directoryPath = storage_path("app/public/campaign/$directoryName");
                $this->extractAndStoreZipFile($request, $directoryPath);
            } elseif ($file->getClientOriginalExtension() === 'html') {
                Log::info('Arquivo HTML detectado. Armazenando.');
                // Salva o arquivo .html no diretório
                $savedPath = $file->storeAs(
                    "campaign/{$hash}",
                    'index.blade.php',
                    'public'
                );


                Log::info('Arquivo HTML salvo em: ' . $savedPath);
            } else {
                Log::warning('Tipo de arquivo desconhecido enviado.', ['file_extension' => $file->getClientOriginalExtension()]);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao processar o arquivo.', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Erro ao processar o arquivo.');
        }
    
        // Armazenamento dos dados no banco
        try {
            Log::info('Salvando dados da campanha no banco de dados.');
            
            $xid = Str::random(11);

            $campaign = new Campaign([
                'name' => $request->name,
                'domain_id' => $request->domain,
                'language' => $request->language,
                'traffic_source' => $request->traffic_source,
                'safe_page' => $savedPath,
                'method_safe' => $request->method_safe,
                'method_offer' => $request->method_offer,
                'offer_pages' => json_encode($request->offer_pages), // Converter para JSON
                'target_countries' => $request->target_countries ? json_encode($request->target_countries) : null,
                'target_devices' => $request->target_devices ? json_encode($request->target_devices) : null,
                'hash' => $hash,
                'xid' => $xid,
            ]);
    
            $campaign->save();
            Log::info('Campanha salva com sucesso. ID da campanha: ' . $campaign->id);
        } catch (\Exception $e) {
            Log::error('Erro ao salvar a campanha no banco de dados.', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Erro ao salvar a campanha.');
        }
    
        // Redireciona ou retorna uma resposta de sucesso
        return redirect()->route('view.rabbit.campaigns')->with('success', [
            'message' => 'Campanha criada com sucesso!',
            'campaignCreated' => $campaign
        ]);
    }
    

    private function extractAndStoreZipFile(Request $request, string $directoryPath, $shopifyIndex)
    {
        // Criação do diretório para armazenar os arquivos extraídos
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }

        // Salvar o arquivo .zip na pasta temporária
        $zipFile = $request->file('safe_page');
        $zipPath = $zipFile->storeAs('temp', 'index.zip', 'public');

        // Caminho absoluto para o arquivo .zip
        $zipFilePath = storage_path('app/public/'.$zipPath);

        // Usando a classe ZipArchive para descompactar o arquivo .zip
        $zip = new \ZipArchive;
        $res = $zip->open($zipFilePath);

        if ($res === true) {
            // Extrair os arquivos para o diretório de destino
            $zip->extractTo($directoryPath);
            $zip->close();

            // Verificar se o arquivo index.html existe no diretório extraído
            $indexFilePath = $directoryPath . '/index.html'; // Ajuste conforme o nome ou tipo do arquivo desejado

            if (file_exists($indexFilePath)) {
                // Atualizar o caminho do arquivo no registro da ShopifyIndex
                $shopifyIndex->update(['index_file_path' => $indexFilePath]);

                // Remover o arquivo .zip temporário, se existir
                if (file_exists($zipFilePath)) {
                    unlink($zipFilePath);
                }
            } else {
                // Se não encontrar o index.html, retornar erro
                return redirect()->back()->with('error', 'O arquivo index.html não foi encontrado no arquivo .zip.');
            }
        } else {
            // Caso ocorra algum erro ao tentar abrir o arquivo .zip
            return redirect()->back()->with('error', 'Erro ao descompactar o arquivo. Tente novamente.');
        }
    }


    public function campaignDelete($id){

        $campaign = Campaign::findOrFail($id);
    
        $campaign->delete();
    
        return redirect()->back()->with('sucess', 'Campanha Deletada Com Sucesso');
    }
    
    
    
    public function campaignEdit($id){
    
        $campaign = Campaign::findOrFail($id);
    
        return view('rabbit.campaignEdit', compact('campaign'));    
    }

    public function campaignEditUpdate(Request $request)
{
    $campaign = Campaign::findOrFail($request->campaign_id);

    if ($request->name) {
        $campaign->name = $request->name;
        $campaign->save();
    }

    if ($request->offer_pages) {
        // Escapa as barras corretamente ao salvar no formato desejado
        $encodedLinks = json_encode($request->offer_pages, JSON_UNESCAPED_SLASHES);
        $campaign->offer_pages = $encodedLinks;
        $campaign->save();
    }

    return redirect()->route('view.rabbit.campaigns');
}


public function campaignVizu($id){
    $campaign = Campaign::findOrFail($id);
    

    return view('rabbit.campaignVizu', compact('campaign'));
}

}
