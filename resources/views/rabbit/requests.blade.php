@extends('layouts.rabbit.app')

@section('title', 'Requests')

@section('content')
<!-- Main Content -->
<div class="mt-6 bg-zinc-800 p-4 rounded-lg">
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
            @foreach ($requests as $request)
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
        {{ $requests->links() }} <!-- Exibe os links de navegação -->
    </div>
</div>
@endsection
