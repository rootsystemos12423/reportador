<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Box para Cadastrar Site -->
                    <a href="{{ route('sitecheck') }}"
                        class="flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-6 rounded-lg shadow-md transition duration-300 transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                        </svg>
                        Cadastrar Site
                    </a>

                    <!-- Box para Reportar Site -->
                    <a href="{{ route('sitereport') }}"
                        class="flex items-center justify-center bg-red-600 hover:bg-red-700 text-white font-bold py-6 rounded-lg shadow-md transition duration-300 transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M4.5 12a7.5 7.5 0 0115 0 7.5 7.5 0 01-15 0z" />
                        </svg>
                        Reportar Site
                    </a>

                    <!-- Box para Cadastrar LP -->
                    <a href="{{ route('landing') }}"
                        class="flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-bold py-6 rounded-lg shadow-md transition duration-300 transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M4.5 12a7.5 7.5 0 0115 0 7.5 7.5 0 01-15 0z" />
                        </svg>
                        Cadastrar LadingPage
                    </a>

                     <!-- Box White Rabbit -->
                <a href="{{ route('view.rabbit') }}"
                     class="flex items-center justify-center bg-purple-600 hover:bg-purple-700 text-white font-bold py-6 rounded-lg shadow-md transition duration-300 transform hover:scale-105">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-3">
                         <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M4.5 12a7.5 7.5 0 0115 0 7.5 7.5 0 01-15 0z" />
                     </svg>
                     White Rabbit
                 </a>
            </div>

            </div>
        </div>
    </div>
</x-app-layout>
