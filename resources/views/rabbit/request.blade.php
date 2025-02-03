@extends('layouts.rabbit.app')

@section('title', 'Detalhes da Requisição')

@section('content')
    <div class="container mx-auto py-8">
        <div class="p-8 rounded-lg shadow-xl border border-gray-700 bg-gray-800 text-white">
            <h1 class="text-3xl font-semibold text-gray-100 mb-6">Detalhes da Requisição #{{ $request->id }}</h1>

            <div class="space-y-6">
                <div class="p-4 rounded-lg border border-gray-600 bg-gray-700">
                    <h2 class="text-xl font-semibold text-gray-300 mb-4">Informações da Requisição</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div><strong>IP:</strong> <span class="text-gray-400">{{ $request->ip }}</span></div>
                        <div><strong>Continente:</strong> <span class="text-gray-400">{{ $request->continent ?? 'N/A' }}</span></div>
                        <div><strong>País:</strong> <span class="text-gray-400">{{ $request->country ?? 'N/A' }}</span></div>
                        <div><strong>Código do País:</strong> <span class="text-gray-400">{{ strtolower($request->country_code) ?? 'N/A' }}</span></div>
                        <div><strong>Fuso horário:</strong> <span class="text-gray-400">{{ $request->timezone ?? 'N/A' }}</span></div>
                        <div><strong>ISP:</strong> <span class="text-gray-400">{{ $request->isp ?? 'N/A' }}</span></div>
                        <div><strong>Organização:</strong> <span class="text-gray-400">{{ $request->org ?? 'N/A' }}</span></div>
                        <div><strong>ASN:</strong> <span class="text-gray-400">{{ $request->asn ?? 'N/A' }}</span></div>
                        <div><strong>Reverse DNS:</strong> <span class="text-gray-400">{{ $request->reverse_dns ?? 'N/A' }}</span></div>
                        <div><strong>Linguagem:</strong> <span class="text-gray-400">{{ $request->language ?? 'N/A' }}</span></div>
                        <div><strong>Dispositivo:</strong> <span class="text-gray-400">{{ $request->device ?? 'N/A' }}</span></div>
                        <div><strong>User Agent:</strong> <span class="text-gray-400">{{ $request->user_agent ?? 'N/A' }}</span></div>
                        <div><strong>Permissão:</strong> <span class="text-gray-400">{{ $request->allowed ? 'Permitido' : 'Não Permitido' }}</span></div>
                        <div><strong>Motivo:</strong> <span class="text-gray-400">{{ $request->reason ?? 'N/A' }}</span></div>
                    </div>
                </div>

                <hr class="my-6 border-gray-600">

                <div class="p-4 rounded-lg border border-gray-600 bg-gray-700">
                    <h2 class="text-xl font-semibold text-gray-300 mb-4">Campanha Relacionada</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div><strong>Nome da Campanha:</strong> <span class="text-gray-400">{{ $request->campaign->name ?? 'N/A' }}</span></div>
                        <div><strong>Idioma da Campanha:</strong> <span class="text-gray-400">{{ $request->campaign->language ?? 'N/A' }}</span></div>
                        <div><strong>Códigos de Países Alvo:</strong> <span class="text-gray-400">{{ implode(', ', json_decode($request->campaign->target_countries)) ?? 'N/A' }}</span></div>
                        <div><strong>Dispositivos Alvo:</strong> <span class="text-gray-400">{{ implode(', ', json_decode($request->campaign->target_devices)) ?? 'N/A' }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
