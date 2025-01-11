<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\LandingPage;

class CheckDomain
{
    public function handle(Request $request, Closure $next)
    {
        // Obter o domínio da requisição
        $domain = $request->getHost(); // Pega o domínio da URL (exemplo: futmantoss.com)

        // Procurar a landing page associada a este domínio
        $landingPage = LandingPage::whereHas('domain', function($query) use ($domain) {
            $query->where('domain', $domain);
        })->first();

        // Se não encontrar, retornar um erro 404
        if (!$landingPage) {
            abort(404, 'Landing Page não encontrada para este domínio');
        }

        // Se encontrar, passar a landing page para a view
        view()->share('landingPage', $landingPage);

        return $next($request);
    }
}
