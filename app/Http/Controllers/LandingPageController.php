<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BackupLink;
use Illuminate\Support\Facades\File;
use App\Models\LandingPage;


class LandingPageController extends Controller
{
    public function view(){

        $landingPages = \App\Models\LandingPage::all();

        $shopify = \App\Models\ShopifyIndex::all();

        $backupLinks = BackupLink::all();

        return view('landing', compact('landingPages', 'backupLinks', 'shopify'));
    }

    public function destroy($id)
    {
        // Buscar a landing page pelo ID
        $landingPage = LandingPage::findOrFail($id);
    
        // Caminho do diretório da landing page no armazenamento
        $directoryPath = storage_path('app/public/landing_pages/' . $landingPage->domain->domain);
    
        // Verificar se o diretório existe e deletá-lo
        if (File::exists($directoryPath)) {
            File::deleteDirectory($directoryPath);
        }
    
        // Excluir a landing page do banco de dados
        $landingPage->delete();
    
        // Redirecionar de volta com uma mensagem de sucesso
        return redirect()->route('landing')->with('success', 'Landing Page e diretório excluídos com sucesso!');
    }

    public function edit($id)
    {
        $landingPage = LandingPage::findOrFail($id);
        return view('landingPages.edit', compact('landingPage'));
    }


    public function show(Request $request)
    {
        // Obter o domínio da requisição
        $domain = $request->getHost(); // Exemplo: futmantoss.com


        // Procurar a landing page associada a este domínio
        $landingPage = \App\Models\LandingPage::whereHas('domain', function ($query) use ($domain) {
            $query->where('domain', $domain);
        })->first();

        // Se não encontrar, retornar um erro 404
        if (!$landingPage) {
            abort(404, 'Landing Page não encontrada para este domínio');
        }

        $dynamicUrl = BackupLink::where('landing_page_id', $landingPage->id)->first();

        if (!$dynamicUrl) {
            abort(404, 'Backup Link não cadastrado');
        }

       // Buscar o ShopifyIndex associado ao backup_link_id
        $shopify = \App\Models\ShopifyIndex::where('backup_link_id', $dynamicUrl->id)->first();

        if ($shopify) {
            // Caminho para o diretório de extração
            $directoryPath = $shopify->index_file_path;  // Ajuste conforme necessário

            // Caminho do arquivo index.html dentro do diretório descompactado
            $indexFilePath = $directoryPath; // Ajuste se necessário, caso o nome ou tipo do arquivo seja diferente

            if (file_exists($indexFilePath)) {
                // Ler o conteúdo do arquivo index.html
                $content = file_get_contents($indexFilePath);
        
                // Ajustar os caminhos relativos
                $baseUrl = url($directoryPath); // URL base para os recursos
                $content = preg_replace(
                    '/(src)=["\'](?!http|\/\/)([^"\']+)["\']/', 
                    '$1="' . $baseUrl . '/$2"',
                    $content
                );
        
                // Retornar o conteúdo ajustado
                return response($content, 200)
                    ->header('Content-Type', 'text/html; charset=UTF-8')
                    ->header('Content-Disposition', 'inline; filename="index.html"');
            } else {
                // Se o arquivo index.html não existir no diretório descompactado
                return response('O arquivo index.html não foi encontrado.', 404);
            }
        }


        // Construir o caminho para o arquivo Blade no sistema de arquivos
        $templatePath = storage_path('app/public/landing_pages/' . $landingPage->domain->domain . '/index.blade.php');

        // Verificar se o parâmetro ?acesDirectPage está presente na URL
        $isDirectPage = $request->has('acesDirectPage'); // Verifica se o parâmetro existe

        if ($isDirectPage && $dynamicUrl) {
            // Verifica se existe um link dinâmico antes de redirecionar
            return redirect()->to($dynamicUrl->url); // Supondo que o campo URL seja 'url'
        }

        // Verificar se o arquivo existe
        if (!file_exists($templatePath)) {
            abort(404, 'Template não encontrado para esta landing page');
        }

        // Renderizar o template diretamente
        return view()->file($templatePath, [
            'landingPage' => $landingPage,
            'dynamicUrl' => $dynamicUrl->url, // URL dinâmica
        ]);
    }



    public function storeBackupLinks(Request $request)
    {
        // Validar os dados do formulário
        $request->validate([
            'landing_page_id' => 'required|exists:landing_pages,id',  // Verificar se a landing page existe
            'backup_url' => 'required|url',  // Verificar se a URL é válida
        ]);

        // Criar o link de backup
        $backupLink = BackupLink::create([
            'landing_page_id' => $request->landing_page_id,  // Associar à landing page
            'url' => $request->backup_url,  // URL fornecida
        ]);

        // Redirecionar de volta com sucesso
        return redirect()->back()->with('success', 'Link de backup cadastrado com sucesso!');
    }


    public function storeAndLanding(Request $request)
{
    // Validação dos campos
    $request->validate([
        'name' => 'required|string|max:255',
        'index_file' => 'required', // 10 MB
        'domain' => 'required|unique:domains,domain',
    ]);

    // Criação da landing page no banco
    $landingPage = \App\Models\LandingPage::create([
        'name' => $request->name,
    ]);

    // Armazenar o arquivo index.html
    $path = $request->file('index_file')->storeAs(
        "landing_pages/{$request->domain}",
        'index.blade.php',
        'public'
    );

    // Criar o domínio associado à landing page
    \App\Models\Domain::create([
        'landing_page_id' => $landingPage->id,
        'domain' => $request->domain,
    ]);

    // Atualizar o caminho do arquivo no registro da landing page
    $landingPage->update(['index_file_path' => $path]);

    // Redirecionar com mensagem de sucesso
    return redirect()->route('landing')->with('success', 'Domínio e Landing Page cadastrados com sucesso!');
}


public function storeShopifyLanding(Request $request, $id)
{
    // Validação dos campos
    $request->validate([
        'index_file' => 'required|mimes:zip', // Verifica que é um arquivo .zip
    ]);


    // Verificar se o backup_link_id é válido
    $backupLink = \App\Models\BackupLink::find($id);
    if (!$backupLink) {
        return redirect()->back()->with('error', 'Backup link não encontrado.');
    }


    // Criar o registro na tabela ShopifyIndex
    $shopifyIndex = \App\Models\ShopifyIndex::create([
        'backup_link_id' => $id,
    ]);

    // Gerar um nome único para o diretório
    $directoryName = uniqid('pressel_', true);
    $directoryPath = storage_path("app/public/pressel/$directoryName");

    // Criação do diretório para armazenar os arquivos extraídos
    if (!file_exists($directoryPath)) {
        mkdir($directoryPath, 0777, true);
    }

    // Salvar o arquivo .zip na pasta temporária
    $zipFile = $request->file('index_file');
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

            // Verificar se o arquivo .zip temporário existe antes de tentar excluir
            if (file_exists($zipFilePath)) {
                if (unlink($zipFilePath)) {
    
                }
            }
            // Redirecionar com mensagem de sucesso
            return redirect()->route('landing')->with('success', 'Domínio e Landing Page cadastrados com sucesso!');
        } else {
            // Se não encontrar o index.html, retornar erro
            return redirect()->back()->with('error', 'O arquivo index.html não foi encontrado no arquivo .zip.');
        }        
    } else {
        // Caso ocorra algum erro ao tentar abrir o arquivo .zip
        return redirect()->back()->with('error', 'Erro ao descompactar o arquivo. Tente novamente.');
    }
}




public function destroyBackup($id)
{
    // Localizar a landing page pelo ID
    $landingPage = BackupLink::findOrFail($id);

    // Excluir o item
    $landingPage->delete();

    // Redirecionar de volta com uma mensagem de sucesso
    return redirect()->route('landing')->with('success', 'Landing Page excluída com sucesso');
}


}
