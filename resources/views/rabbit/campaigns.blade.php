@extends('layouts.rabbit.app')

@section('title', 'Campaign')

@section('content')
<!-- Main Content -->

@if(session('success'))
@php
    $campaignCreated = session('success')['campaignCreated'];
@endphp
<div x-data="{ showModal: true }">
    <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
        <!-- Modal Container -->
        <div x-show="showModal" x-transition 
             class="bg-gray-900 text-white rounded-lg shadow-lg w-[550px] p-6 relative" 
             @click.away="showModal = false">
            
            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b border-gray-700 pb-3">
                <h2 class="text-2xl font-bold">Domains</h2>
                <button @click="showModal = false" class="text-gray-400 hover:text-purple-500 text-4xl">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="mt-4">
                <label for="domain" class="text-xl font-bold">Your campaign name: 
                    <span class="text-purple-500">{{ $campaignCreated->name }}</span>
                </label>
                <small class="block mt-1 text-gray-400">
                    Copy the data below to fill them in when creating your campaign on the advertising platform.
                </small>

                <!-- Informações adicionais -->
                <div class="mt-4 space-y-3">
                    <!-- URL -->
                    <div class="bg-gray-700 p-4 rounded-lg flex justify-between items-center">
                        <p class="flex-1 text-sm overflow-hidden text-ellipsis" title="{{ $campaignCreated->domain->domain }}/{{ $campaignCreated->hash }}">
                            {{ $campaignCreated->domain->domain }}/{{ $campaignCreated->hash }}
                        </p>
                        <button class="text-purple-500" onclick="copyText('url')">
                            <i class="fa-regular fa-copy text-2xl"></i>
                        </button>
                    </div>
                    <!-- URL Parameters -->
                    <div class="bg-gray-700 p-4 rounded-lg flex justify-between items-center">
                        <p class="flex-1 text-sm overflow-hidden text-ellipsis" title="{lpurl}&cwr={campaignid}&twr={targetid}&gwr={adgroupid}&domain={domain}&cr={creative}&plc={placement}&mtx={matchtype}&rdn={random}&kw={keyword}&cpc={ifsearch:cpc}&disp={ifcontent:display}&int={loc_interest_ms}&loc={loc_physical_ms}&net={network}&pos={adposition}&dev={device}&gclid={gclid}&wbraid={wbraid}&gbraid={gbraid}&ref_id={gclid}&xid={{ $campaignCreated->xid }}">
                            {lpurl}&cwr={campaignid}&twr={targetid}&gwr={adgroupid}&domain={domain}&cr={creative}&plc={placement}&mtx={matchtype}&rdn={random}&kw={keyword}&cpc={ifsearch:cpc}&disp={ifcontent:display}&int={loc_interest_ms}&loc={loc_physical_ms}&net={network}&pos={adposition}&dev={device}&gclid={gclid}&wbraid={wbraid}&gbraid={gbraid}&ref_id={gclid}&xid={{ $campaignCreated->xid }}
                        </p>
                        <button class="text-purple-500" onclick="copyText('params')">
                            <i class="fa-regular fa-copy text-2xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copyText(type) {
        let text = '';
        
        if (type === 'url') {
            text = '{{ $campaignCreated->domain->domain }}/{{ $campaignCreated->hash }}';
        } else if (type === 'params') {
            text = '{lpurl}&cwr={campaignid}&twr={targetid}&gwr={adgroupid}&domain={domain}&cr={creative}&plc={placement}&mtx={matchtype}&rdn={random}&kw={keyword}&cpc={ifsearch:cpc}&disp={ifcontent:display}&int={loc_interest_ms}&loc={loc_physical_ms}&net={network}&pos={adposition}&dev={device}&gclid={gclid}&wbraid={wbraid}&gbraid={gbraid}&ref_id={gclid}&xid={{ $campaignCreated->xid }}';
        }

        // Create a temporary input element
        const tempInput = document.createElement('input');
        tempInput.value = text;
        document.body.appendChild(tempInput);

        // Select and copy the text
        tempInput.select();
        document.execCommand('copy');

        // Remove the temporary input
        document.body.removeChild(tempInput);

        // Optionally, show a message to the user that the text was copied
    }
</script>
@endif

     <div class="mt-6 bg-gray-800 p-4 rounded-lg">
         <table class="w-full text-left">
             <thead>
                 <tr class="border-b border-gray-700">
                     <th class="p-2">Hash</th>
                     <th class="p-2">Name</th>
                     <th class="p-2">Traffic Source</th>
                     <th class="p-2">Date</th>
                     <th class="p-2">Active</th>
                     <th class="p-2">Actions</th>
                 </tr>
             </thead>
             <tbody>
                @foreach ($campaigns as $campaign)
                <tr class="border-b border-gray-700">
                    <td class="p-2">{{ $campaign->hash }}</td>
                    <td class="p-2">{{ $campaign->name }}</td>
                    <td class="p-2 text-lg font-bold">{{ $campaign->traffic_source }}</td>
                    <td class="p-2">{{ \Carbon\Carbon::parse($campaign->created_at)->format('d/m/Y') }}</td>
                    <td class="p-2"><i class="fa-solid text-xl text-green-500 fa-check"></i></td>
                    <td class="p-2">
                      <a href="{{ route('delete.rabbit.campaign', ['id' => $campaign->id]) }}" class="p-2 text-purple-500">
                            <i class="fa-solid fa-trash text-2xl"></i>
                        </a>    
                      <a href="{{ route('edit.rabbit.campaign', ['id' => $campaign->id]) }}" class="p-2 text-purple-500">
                        <i class="fa-solid fa-pen text-2xl"></i>
                    </a> 
                      <a class="p-2 text-xl text-purple-600" href="#"><i class="fa-solid fa-eye"></i></i></a>
                    </td>
                </tr>
                @endforeach
             </tbody>
         </table>
     </div>
@endsection
