<x-app-layout>
      <x-slot name="header">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
              {{ __('Cadastrar Domínio e Webhook') }}
          </h2>
      </x-slot>
  
      <div class="py-12">
          <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
              <!-- Mensagem de sucesso -->
              @if (session('success'))
                  <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg">
                      {{ session('success') }}
                  </div>
              @endif
  
              <!-- Formulário para cadastrar domínio -->
              <div class="bg-white shadow-xl sm:rounded-lg p-8 mb-8">
                  <h3 class="text-2xl font-bold mb-6">Cadastrar um Novo Domínio</h3>
                  <form method="POST" action="{{ route('site.store') }}">
                      @csrf
                      <div class="mb-6">
                          <label for="domain" class="block text-gray-700 font-medium mb-2">Domínio do Site:</label>
                          <input type="text" name="domain" id="domain" placeholder="exemplo.com"
                              class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                              required>
                      </div>
                      <div class="flex justify-end">
                          <button type="submit"
                              class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200">
                              Cadastrar
                          </button>
                      </div>
                  </form>
              </div>
  
              <!-- Formulário para cadastrar webhook -->
              <div class="bg-white shadow-xl sm:rounded-lg p-8 mb-8">
                  <h3 class="text-2xl font-bold mb-6">Cadastrar um Novo Webhook</h3>
                  <form method="POST" action="{{ route('webhook.store') }}">
                      @csrf
                      <div class="mb-6">
                        <label for="name" class="block text-gray-700 font-medium mb-2">Nome do Webhook:</label>
                        <input type="text" name="name" id="name" placeholder="Webhook Nome"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                            required>
                    </div>
                      <div class="mb-6">
                          <label for="url" class="block text-gray-700 font-medium mb-2">URL do Webhook:</label>
                          <input type="text" name="url" id="url" placeholder="https://exemplo.com/webhook"
                              class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                              required>
                      </div>
                      <div class="flex justify-end">
                          <button type="submit"
                              class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200">
                              Cadastrar Webhook
                          </button>
                      </div>
                  </form>
              </div>
  
              <!-- Tabela para listar sites registrados -->
              <div class="bg-white shadow-xl sm:rounded-lg p-8">
                  <h3 class="text-2xl font-bold mb-6">Lista de Sites Registrados</h3>
  
                  <!-- Verifica se existem sites cadastrados -->
                  @if($sites->isEmpty())
                      <p class="text-gray-600">Nenhum site cadastrado ainda.</p>
                  @else
                      <table class="w-full border-collapse">
                          <thead>
                              <tr>
                                  <th class="border-b-2 border-gray-200 p-4 text-left">ID</th>
                                  <th class="border-b-2 border-gray-200 p-4 text-left">Domínio</th>
                                  <th class="border-b-2 border-gray-200 p-4 text-left">Data de Cadastro</th>
                                  <th class="border-b-2 border-gray-200 p-4 text-left">Ações</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach($sites as $site)
                                  <tr>
                                      <td class="border-b border-gray-200 p-4">{{ $site->id }}</td>
                                      <td class="border-b border-gray-200 p-4">{{ $site->domain }}</td>
                                      <td class="border-b border-gray-200 p-4">{{ $site->created_at->format('d/m/Y H:i') }}</td>
                                      <td class="border-b border-gray-200 p-4">
                                          <form method="POST" action="{{ route('site.destroy', $site->id) }}" onsubmit="return confirm('Tem certeza que deseja excluir este domínio?');">
                                              @csrf
                                              @method('DELETE')
                                              <button type="submit" class="text-red-600 hover:text-red-800">Excluir</button>
                                          </form>
                                      </td>
                                  </tr>
                              @endforeach
                          </tbody>
                      </table>
                  @endif
              </div>
  
              <!-- Tabela para listar webhooks cadastrados -->
              <div class="bg-white shadow-xl sm:rounded-lg p-8">
                  <h3 class="text-2xl font-bold mb-6">Lista de Webhooks Cadastrados</h3>
  
                  <!-- Verifica se existem webhooks cadastrados -->
                  @if($webhooks->isEmpty())
                      <p class="text-gray-600">Nenhum webhook cadastrado ainda.</p>
                  @else
                      <table class="w-full border-collapse">
                          <thead>
                              <tr>
                                  <th class="border-b-2 border-gray-200 p-4 text-left">ID</th>
                                  <th class="border-b-2 border-gray-200 p-4 text-left">URL</th>
                                  <th class="border-b-2 border-gray-200 p-4 text-left">Ações</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach($webhooks as $webhook)
                                  <tr>
                                      <td class="border-b border-gray-200 p-4">{{ $webhook->id }}</td>
                                      <td class="border-b border-gray-200 p-4">{{ $webhook->url }}</td>
                                      <td class="border-b border-gray-200 p-4">
                                          <form method="POST" action="{{ route('webhook.destroy', $webhook->id) }}" onsubmit="return confirm('Tem certeza que deseja excluir este webhook?');">
                                              @csrf
                                              @method('DELETE')
                                              <button type="submit" class="text-red-600 hover:text-red-800">Excluir</button>
                                          </form>
                                      </td>
                                  </tr>
                              @endforeach
                          </tbody>
                      </table>
                  @endif
              </div>
          </div>
      </div>
  </x-app-layout>
  