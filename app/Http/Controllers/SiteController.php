<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\PushcutWebhook;
use App\Models\EmailConfig;


class SiteController extends Controller
{

    public function show(){
        
        $sites = Site::all();
        $webhooks = PushcutWebhook::all();

        return view('sitecheck', compact('sites', 'webhooks'));
    }

    public function store(Request $request)
    {
        // Validação dos dados
        $validatedData = $request->validate([
            'domain' => 'required|string|unique:sites,domain|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        // Salvar no banco de dados
        Site::create($validatedData);

        return redirect()->route('sitecheck')->with('success', 'Domínio cadastrado com sucesso!');
    }

    public function destroy($id)
    {
        $site = Site::findOrFail($id);
        $site->delete();

        return redirect()->route('sitecheck')->with('success', 'Domínio excluído com sucesso!');
    }

    public function index(){

        $configs = EmailConfig::all();

        return view('sitereport', compact('configs'));
    }

    public function destroyConfig($id)
{
    $config = EmailConfig::findOrFail($id);
    $config->delete();
    return redirect()->route('sitereport')->with('success', 'Configuração excluída com sucesso!');
}

}
