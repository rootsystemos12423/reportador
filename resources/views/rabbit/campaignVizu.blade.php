@extends('layouts.rabbit.app')

@section('title', 'Campaign Detailed')

@section('content')

<div class="container mx-auto p-6 bg-gray-800 text-white rounded-lg shadow-md">
    <!-- Título da Página -->
    <div class="text-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-100">Detalhes da Campanha: {{ $campaign->name }}</h1>
    </div>

    <!-- Tabs -->
    <div x-data="{ tab: 'campaign' }">
        <div class="flex border-b border-gray-600">
            <button @click="tab = 'campaign'" 
                :class="{ 'border-purple-600 text-purple-400': tab === 'campaign' }"
                class="px-4 py-2 text-gray-300 hover:text-white border-b-2">
                Campaign
            </button>
            <button @click="tab = 'charts'" 
                :class="{ 'border-purple-600 text-purple-400': tab === 'charts' }"
                class="px-4 py-2 text-gray-300 hover:text-white border-b-2">
                Charts
            </button>
            <button @click="tab = 'requests'" 
                :class="{ 'border-purple-600 text-purple-400': tab === 'requests' }"
                class="px-4 py-2 text-gray-300 hover:text-white border-b-2">
                Requests
            </button>
        </div>

        <!-- Campaign Tab -->
        <div x-show="tab === 'campaign'" class="mt-6 space-y-6">
            <div class="space-y-6">
                <!-- Nome da Campanha -->
                <div class="flex justify-between bg-gray-700 p-4 rounded-lg shadow-sm">
                    <div class="text-gray-400">Nome da Campanha:</div>
                    <div class="font-medium text-gray-100">{{ $campaign->name }}</div>
                </div>
        
                <!-- Domínio -->
                <div class="flex justify-between bg-gray-700 p-4 rounded-lg shadow-sm">
                    <div class="text-gray-400">Domínio:</div>
                    <div class="font-medium text-gray-100">{{ rtrim($campaign->domain->domain, '/') }}/{{ ltrim($campaign->hash, '/') }}</div>
                </div>
      
                <div class="flex justify-between bg-gray-700 p-4 rounded-lg shadow-sm">
                  <div class="text-gray-400">URL PARAMS:</div>
                  <div class="font-medium text-gray-100 text-left">{lpurl}&cwr={campaignid}&twr={targetid}&gwr={adgroupid}&domain={domain}&cr={creative}&plc={placement}&mtx={matchtype}&rdn={random}&kw={keyword}&cpc={ifsearch:cpc}&disp={ifcontent:display}&int={loc_interest_ms}&loc={loc_physical_ms}&net={network}&pos={adposition}&dev={device}&gclid={gclid}&wbraid={wbraid}&gbraid={gbraid}&ref_id={gclid}&xid={{ $campaign->xid }}</div>
              </div>
        
                <!-- Linguagem -->
                <div class="flex justify-between bg-gray-700 p-4 rounded-lg shadow-sm">
                    <div class="text-gray-400">Linguagem:</div>
                    <div class="font-medium text-gray-100">{{ $campaign->language }}</div>
                </div>
        
                <!-- Fonte de Tráfego -->
                <div class="flex justify-between bg-gray-700 p-4 rounded-lg shadow-sm">
                    <div class="text-gray-400">Fonte de Tráfego:</div>
                    <div class="font-medium text-gray-100">{{ $campaign->traffic_source }}</div>
                </div>
        
                <!-- Página de Oferta -->
                <div class="flex justify-between bg-gray-700 p-4 rounded-lg shadow-sm">
                    <div class="text-gray-400">Página de Oferta:</div>
                    <div class="font-medium text-blue-400">
                        @foreach (json_decode($campaign->offer_pages) as $page)
                            <a href="{{ $page }}" target="_blank" class="underline">{{ $page }}</a><br>
                        @endforeach
                    </div>
                </div>
        
                <!-- Método Safe -->
                <div class="flex justify-between bg-gray-700 p-4 rounded-lg shadow-sm">
                    <div class="text-gray-400">Método Safe:</div>
                    <div class="font-medium text-gray-100">{{ $campaign->method_safe }}</div>
                </div>
        
                <!-- Método da Oferta -->
                <div class="flex justify-between bg-gray-700 p-4 rounded-lg shadow-sm">
                    <div class="text-gray-400">Método da Oferta:</div>
                    <div class="font-medium text-gray-100">{{ $campaign->method_offer }}</div>
                </div>
        
                <!-- Países Alvo -->
                <div class="flex justify-between bg-gray-700 p-4 rounded-lg shadow-sm">
                    <div class="text-gray-400">Países Alvo:</div>
                    <div class="font-medium text-gray-100">
                        @foreach (json_decode($campaign->target_countries) as $country)
                            <span>{{ $country }}</span><br>
                        @endforeach
                    </div>
                </div>
        
                <!-- Dispositivos Alvo -->
                <div class="flex justify-between bg-gray-700 p-4 rounded-lg shadow-sm">
                    <div class="text-gray-400">Dispositivos Alvo:</div>
                    <div class="font-medium text-gray-100">
                        @foreach (json_decode($campaign->target_devices) as $device)
                            <span>{{ $device }}</span><br>
                        @endforeach
                    </div>
                </div>
        
                <!-- Data de Criação -->
                <div class="flex justify-between bg-gray-700 p-4 rounded-lg shadow-sm">
                    <div class="text-gray-400">Data de Criação:</div>
                    <div class="font-medium text-gray-100">{{ $campaign->created_at->format('d/m/Y H:i') }}</div>
                </div>
        
                <!-- Data de Atualização -->
                <div class="flex justify-between bg-gray-700 p-4 rounded-lg shadow-sm">
                    <div class="text-gray-400">Data de Atualização:</div>
                    <div class="font-medium text-gray-100">{{ $campaign->updated_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Charts Tab -->
        <div x-show="tab === 'charts'" class="mt-6 space-y-6">
            <script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>

            <!-- Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mt-6">
                <div class="bg-gray-800 p-6 rounded-lg shadow-md text-center transform transition duration-300 hover:scale-105">
                    <i class="fas fa-chart-line text-pink-400 text-3xl"></i>
                    <p class="text-pink-400 font-semibold mt-2">Total Requests</p>
                    <p class="text-3xl font-bold">{{ array_sum($totalRequests) }}</p>
                </div>
                <div class="bg-gray-800 p-6 rounded-lg shadow-md text-center transform transition duration-300 hover:scale-105">
                    <i class="fas fa-shield-alt text-purple-400 text-3xl"></i>
                    <p class="text-purple-400 font-semibold mt-2">Safe Page</p>
                    <p class="text-3xl font-bold">{{ array_sum($safePage) }}</p>
                </div>
                <div class="bg-gray-800 p-6 rounded-lg shadow-md text-center transform transition duration-300 hover:scale-105">
                    <i class="fas fa-file-invoice text-blue-400 text-3xl"></i>
                    <p class="text-blue-400 font-semibold mt-2">Offer Page</p>
                    <p class="text-3xl font-bold">{{ array_sum($offerPage) }}</p>
                </div>
            </div>
            
            <!-- Chart -->
            <div class="bg-gray-900 mt-8 rounded-lg shadow-lg overflow-hidden p-6">
                <h2 class="text-xl text-white font-semibold mb-4">Traffic Analytics</h2>
                <div id="chart" class="w-full h-[500px]"></div>
            </div>
            
            <script>
                var chart = echarts.init(document.getElementById('chart'));
            
                var options = {
                    backgroundColor: '#1f2937',
                    textStyle: { color: '#ffffff' },
                    tooltip: { trigger: 'axis' },
                    legend: {
                        data: ['Safe Page', 'Total Requests', 'Offer Page'],
                        bottom: 10,
                        right: 20,
                        orient: 'horizontal',
                        textStyle: { color: '#ffffff', fontSize: 14 },
                        itemGap: 20,
                        itemWidth: 25,
                        itemHeight: 14,
                        backgroundColor: 'rgba(255,255,255,0.1)',
                        borderColor: '#666',
                        borderWidth: 1,
                        borderRadius: 8,
                        padding: [10, 20]
                    },
                    grid: {
                        left: '5%',
                        right: '5%',
                        bottom: '20%',
                        containLabel: true
                    },
                    xAxis: {
                        type: 'category',
                        data: @json($dates),
                        axisLine: { lineStyle: { color: '#888' } },
                        axisLabel: { rotate: 30, fontSize: 12 }
                    },
                    yAxis: {
                        type: 'value',
                        axisLine: { lineStyle: { color: '#888' } }
                    },
                    series: [
                        {
                            name: 'Safe Page',
                            type: 'line',
                            smooth: true,
                            lineStyle: { width: 4 },
                            itemStyle: { color: '#ffcc00' },
                            areaStyle: { color: 'rgba(255,204,0,0.3)' },
                            data: @json($safePage)
                        },
                        {
                            name: 'Total Requests',
                            type: 'line',
                            smooth: true,
                            lineStyle: { width: 4 },
                            itemStyle: { color: '#ff66ff' },
                            areaStyle: { color: 'rgba(255,102,255,0.3)' },
                            data: @json($totalRequests)
                        },
                        {
                            name: 'Offer Page',
                            type: 'line',
                            smooth: true,
                            lineStyle: { width: 4 },
                            itemStyle: { color: '#9933ff' },
                            areaStyle: { color: 'rgba(153,51,255,0.3)' },
                            data: @json($offerPage)
                        }
                    ]
                };
            
                chart.setOption(options);
            </script>
        </div>

        <!-- Requests Tab -->
        <div x-show="tab === 'requests'" class="mt-6 space-y-6">
            <div class="text-gray-400 text-lg">Requests da campanha</div>
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="p-2">Created In</th>
                        <th class="p-2">Campaign Name</th>
                        <th class="p-2">User-Agent</th>
                        <th class="p-2">County</th>
                        <th class="p-2">IP</th>
                        <th class="p-2">Device</th>
                        <th class="p-2">Access</th>
                        <th class="p-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requestsFiltered as $request)
                        <tr class="border-b border-gray-700">
                            <td class="p-2">{{ \Carbon\Carbon::parse($request->created_at)->translatedFormat('l, d \d\e F \d\e Y \à\s H:i:s T') }}</td>
                            <td class="p-2">{{ $request->campaign->name }}</td>
                            <td class="p-2">{{ $request->user_agent }}</td>
                            <td class="p-2"><img src="https://flagcdn.com/{{ strtolower($request->country_code) }}.svg" class="w-10 h-10"></td>
                            <td class="p-2">{{ $request->ip }}</td>
                            <td class="p-2">{{ $request->device }}</td>
                            <td class="p-2">@if($request->allowed === 1)Granted @else Denied @endif</td>
                            <td class="p-2"><a class="p-2 text-xl text-purple-600" href="{{ route('cloacker.rabbit.request.show', ['id' => $request->id]) }}">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        
            <!-- Adicionando links de paginação -->
            <div class="mt-4">
                {{ $requestsFiltered->links() }} <!-- Exibe os links de navegação -->
            </div>
        </div>
    </div>

    <!-- Botão de Voltar -->
    <div class="text-center mt-6">
        <a href="{{ route('view.rabbit.campaigns') }}" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-300">Voltar</a>
    </div>
</div>

@endsection
