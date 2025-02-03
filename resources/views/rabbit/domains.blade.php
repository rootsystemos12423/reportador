@extends('layouts.rabbit.app')

@section('title', 'Domains')

@section('content')
     <div class="mt-6 bg-gray-800 p-4 rounded-lg">
         <table class="w-full text-left">
             <thead>
                 <tr class="border-b border-gray-700">
                     <th class="p-2">Domain</th>
                     <th class="p-2">Valid</th>
                     <th class="p-2">Verify Domain</th>
                     <th class="p-2">Actions</th>
                 </tr>
             </thead>
             <tbody>
                @foreach ($domains as $domain)
                <tr class="border-b border-gray-700">
                    <td class="p-2">{{ $domain->domain }}</td>
                    <td class="p-2"><i class="fa-solid text-xl text-green-500 fa-check"></i></td>
                    <td class="p-2"> </td>
                    <td class="p-2">
                      <a class="p-2 text-xl text-purple-600" href="#"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                @endforeach
             </tbody>
         </table>
     </div>
@endsection
