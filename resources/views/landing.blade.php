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

        <div class="p-6 bg-gray-50 shadow rounded-lg mt-8">
            <h2 class="text-xl font-semibold mb-4">Cadastrar Links de Backup</h2>
            <form action="{{ route('backup_links.store') }}" method="POST" class="space-y-6">
                @csrf
        
                <!-- Nome do Link de Backup -->
                <div>
                    <label for="backup_landing_page" class="block text-sm font-medium text-gray-700">
                        Selecionar Landing Page
                    </label>
                    <select name="landing_page_id" id="landing_page_id" 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="" disabled selected>Selecione uma Landing Page</option>
                        @foreach ($landingPages as $landingPage)
                            <option value="{{ $landingPage->id }}">{{ $landingPage->name }}</option>
                        @endforeach
                    </select>
                    @error('backup_landing_page')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
        
                <!-- URL do Link de Backup -->
                <div>
                    <label for="backup_url" class="block text-sm font-medium text-gray-700">
                        URL do Link de Backup
                    </label>
                    <input type="url" name="backup_url" id="backup_url" placeholder="https://exemplo.com/backup"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('backup_url')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
        
                <!-- Botão de Enviar -->
                <div class="flex justify-end">
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 text-white rounded-md shadow-md hover:bg-green-700 focus:outline-none">
                        Cadastrar Link de Backup
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
                            <div class="flex items-center mt-2 space-x-4">
                                <!-- Botão de Editar -->
                                <a href="{{ route('landingPages.edit', $landingPage->id) }}"
                                class="text-yellow-600 hover:text-yellow-800 text-sm underline">
                                    Editar
                                </a>
                                <!-- Formulário de Exclusão -->
                                <form action="{{ route('landingPages.destroy', $landingPage->id) }}" method="POST"
                                    onsubmit="return confirm('Tem certeza que deseja excluir esta Landing Page?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm underline">
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>


            <div class="mt-8">
                <h2 class="text-xl font-medium mb-4">Links de Backup</h2>
                <div class="space-y-4">
                    @foreach ($backupLinks as $backupLink)
                        <div class="bg-gray-50 shadow p-4 rounded-md">
                            <h3 class="text-lg font-semibold text-gray-800">Landing Page: {{ $backupLink->landingPage->name }}</h3>
                            <p class="text-sm text-gray-600">URL de Backup: {{ $backupLink->url }}</p>
                            <p class="text-sm text-gray-600">Landing Page: {{ $backupLink->landingPage->name }}</p>
                            <a href="{{ $backupLink->url }}" target="_blank"
                               class="text-blue-600 hover:text-blue-800 text-sm underline">
                                Visitar Backup
                            </a>
                            <form action="{{ route('delete.backup', $backupLink->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 ml-2 hover:text-red-800 text-sm underline">
                                    Excluir
                                </button>
                            </form>                            
                        </div>
                    @endforeach
                </div>
              
      </div>
  </x-app-layout>
  