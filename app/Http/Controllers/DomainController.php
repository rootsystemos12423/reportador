<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Domain;

class DomainController extends Controller
{
    public function store(Request $request)
{
    // Validação do domínio
    $request->validate([
        'domain' => 'required|url|unique:domains,domain|max:255',
    ]);

    // Criar um novo domínio
    $domain = new Domain();
    $domain->domain = $request->input('domain');
    $domain->save();

    return response()->json([
        'success' => true,
        'message' => 'Domain added successfully!',
        'domain' => $domain
    ]);
}
}
