@extends('layouts.rabbit.app')

@section('title', 'Campaign Detailed')

@section('content')

<div class="container mx-auto p-6 bg-gray-800 text-white rounded-lg shadow-md">
      <!-- Título da Página -->
      <div class="text-center mb-6">
          <h1 class="text-3xl font-semibold text-gray-100">Detalhes da Campanha: {{ $campaign->name }}</h1>
      </div>
  
      <!-- Dados da Campanha -->
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
  
      <!-- Botão de Voltar -->
      <div class="text-center mt-6">
          <a href="{{ route('view.rabbit.campaigns') }}" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-300">Voltar</a>
      </div>
  </div>
  


@endsection
