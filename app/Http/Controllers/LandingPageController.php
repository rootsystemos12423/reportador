<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function view(){

        $landingPages = \App\Models\LandingPage::all();

        return view('landing', compact('landingPages'));
    }


    public function show(Request $request)
    {
        // Obter o domínio da requisição
        $domain = $request->getHost(); // Pega o domínio da URL (exemplo: futmantoss.com)

        // Procurar a landing page associada a este domínio
        $landingPage = \App\Models\LandingPage::whereHas('domain', function ($query) use ($domain) {
            $query->where('domain', $domain);
        })->first();

        // Se não encontrar, retornar um erro 404
        if (!$landingPage) {
            abort(404, 'Landing Page não encontrada para este domínio');
        }

        // Passar a URL da landing page para a view
        return view('landing.index', ['landingPage' => $landingPage, 'url' => $landingPage->url]);
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

}