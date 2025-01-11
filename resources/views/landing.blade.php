<x-app-layout>
      <div class="max-w-6xl mx-auto py-8 px-6 bg-white shadow-md rounded-lg">
          <h1 class="text-2xl font-bold mb-6">Gerenciar Landing Pages e Domínios</h1>
  
          <!-- Mensagens de Sucesso -->
          @if (session('success'))
              <div class="bg-green-100 text-green-800 p-4 rounded-md mb-4">
                  {{ session('success') }}
              </div>
          @endif
  
          <div class="p-6 bg-gray-50 shadow rounded-lg">
            <h2 class="text-xl font-semibold mb-4">Cadastrar Domínio e Landing Page</h2>
            <form action="{{ route('landing_pages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
        
                <!-- Nome da Landing Page -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Nome da Landing Page
                    </label>
                    <input type="text" name="name" id="name" placeholder="Nome da Landing Page" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
        
                <!-- Upload do index.html -->
                <div>
                    <label for="index_file" class="block text-sm font-medium text-gray-700">
                        Arquivo index.html
                    </label>
                    <input type="file" name="index_file" id="index_file" accept=".html" 
                           class="mt-1 block w-full text-gray-500 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('index_file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
        
                <!-- Nome do Domínio -->
                <div>
                    <label for="domain" class="block text-sm font-medium text-gray-700">
                        Domínio
                    </label>
                    <input type="text" name="domain" id="domain" placeholder="exemplo.com" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('domain')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
        
                <!-- Botão de Enviar -->
                <div class="flex justify-end">
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-md shadow-md hover:bg-blue-700 focus:outline-none">
                        Cadastrar Domínio e Landing Page
                    </button>
                </div>
            </form>
        </div>

            <!-- Lista de Domínios Conectados -->
            <div>
                  <h2 class="text-xl font-medium mb-4">Domínios Conectados</h2>
                  <div class="space-y-4">
                      @foreach ($landingPages as $landingPage)
                          <div class="bg-gray-50 shadow p-4 rounded-md">
                              <h3 class="text-lg font-semibold text-gray-800">{{ $landingPage->name }}</h3>
                              <p class="text-sm text-gray-600">Domínio: {{ $landingPage->domain->domain ?? 'Não definido' }}</p>
                              <p class="text-sm text-gray-600">Arquivo: {{ $landingPage->index_file_path }}</p>
                              <a href="{{ $landingPage->domain->domain ?? 'Não definido' }}" target="_blank"
                                 class="text-blue-600 hover:text-blue-800 text-sm underline">
                                  Visitar
                              </a>
                          </div>
                      @endforeach
                  </div>
              </div>
              
      </div>
  </x-app-layout>
  