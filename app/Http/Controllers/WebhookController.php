<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PushcutWebhook;

class WebhookController extends Controller
{
    public function store(Request $request)
    {
        PushcutWebhook::create($request->only('name', 'url'));
        return redirect()->route('sitecheck')->with('success', 'Webhook cadastrado com sucesso!');
    }

    public function destroy($id)
    {
        $webhook = PushcutWebhook::findOrFail($id);
        $webhook->delete();

        return redirect()->route('sitecheck')->with('success', 'Webhook excluído com sucesso!');
    }
}
