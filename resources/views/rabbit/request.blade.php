@extends('layouts.rabbit.app')

@section('title', 'Detalhes da Requisição')

@section('content')
    <div class="container mx-auto py-8">
        <div class="p-8 rounded-lg shadow-xl border border-gray-700 bg-zinc-800 text-white">
            <h1 class="text-3xl font-semibold text-gray-100 mb-6">Detalhes da Requisição #{{ $request->id }}</h1>

            <div class="space-y-6">
                <div class="p-4 rounded-lg border border-gray-600 bg-zinc-700">
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
                        <div><strong>Referrer:</strong> <span class="text-gray-400">{{ $request->referer ?? 'N/A' }}</span></div>
                    </div>
                </div>

                <hr class="my-6 border-gray-600">

                <div class="p-4 rounded-lg border border-gray-600 bg-zinc-700">
                    <h2 class="text-xl font-semibold text-gray-300 mb-4">Parâmetros de Rastreamento</h2>
                    @php
                    // Lista de parâmetros válidos do model
                    $utmColumns = [
                        'cwr', 'twr', 'gwr', 'domain', 'cr', 'plc', 'mtx', 'rdn', 'kw',
                        'cpc', 'disp', 'int', 'loc', 'net', 'pos', 'dev', 'gclid', 
                        'wbraid', 'gbraid', 'xid', 'ref_id', 'created_at', 'updated_at'
                    ];
                
                    // Verifica se $request->utms não é nulo, tem dados e se o índice 0 existe
                    if (isset($request->utms[0]) && !empty($request->utms[0])) {
                        // Pega os parâmetros de rastreamento, assumindo que estão no primeiro índice do array
                        $trackingData = collect($request->utms[0])
                            ->filter(function ($value, $key) use ($utmColumns) {
                                return in_array($key, $utmColumns) && !empty(trim($value));
                            });
                    } else {
                        // Marca como null quando $request->utms estiver vazio ou nulo
                        $trackingData = null;
                    }
                @endphp
                
                @if ($trackingData && $trackingData->isNotEmpty())
                    <div class="overflow-x-auto rounded-lg border border-gray-700">
                        <table class="min-w-full divide-y divide-gray-700">
                            <tbody class="bg-zinc-800 divide-y divide-gray-700">
                                @foreach ($trackingData as $key => $value)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-purple-300">
                                            {{ strtoupper(str_replace('_', ' ', $key)) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ $value }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 text-center text-gray-400 border-2 border-dashed border-gray-700 rounded-lg">
                        Nenhum dado de rastreamento encontrado
                    </div>
                @endif                
                </div>
                
            </div>
        </div>
    </div>
@endsection
