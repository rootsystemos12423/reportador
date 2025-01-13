<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BackupLink;


class LandingPageController extends Controller
{
    public function view(){

        $landingPages = \App\Models\LandingPage::all();

        $backupLinks = BackupLink::all();

        return view('landing', compact('landingPages', 'backupLinks'));
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

        // Construir o caminho para o arquivo Blade no sistema de arquivos
        $templatePath = storage_path('app/public/landing_pages/' . $landingPage->domain->domain . '/index.blade.php');

        // Verificar se o parâmetro ?acesDirectPage está presente na URL
        $isDirectPage = $request->has('acesDirectPage'); // Verifica se o parâmetro existe

        $dynamicUrl = BackupLink::where('landing_page_id', $landingPage->id)->first();

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
        'index_file' => 'required|file|mimes:html|max:10240', // 10 MB
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
