@extends('layouts.rabbit.app')

@section('title', 'Gerenciamento de Regras')

@section('content')
    <div class="container mx-auto py-8">
        <div class="bg-gray-800 text-white p-8 rounded-lg shadow-xl border border-gray-700">
            <h1 class="text-3xl font-semibold text-gray-100 mb-6">Gerenciamento de Regras de Bloqueio</h1>

            {{-- Formulário Estático --}}
            <form action="{{ route('store.rabbit.rules') }}" method="POST" class="space-y-6 bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-700 text-white">
                  @csrf
                  <h2 class="text-2xl font-semibold mb-4 text-gray-200">Criar Regra de Bloqueio</h2>
                
                  {{-- Seletor de Ação --}}
                  <div>
                      <label class="block text-gray-300 mb-2">Ação</label>
                      <select name="action" class="w-full p-3 rounded-md bg-gray-700 border border-gray-600 text-white">
                          <option value="block">Bloquear</option>
                          <option value="allow">Permitir</option>
                      </select>
                  </div>
                
                  {{-- Seletor de Condição --}}
                  <div>
                      <label class="block text-gray-300 mb-2">Condição</label>
                      <select name="condition_operator" class="w-full p-3 rounded-md bg-gray-700 border border-gray-600 text-white">
                          <option value="equal">É igual a</option>
                          <option value="contains">Possui o termo</option>
                          <option value="different">Diferente de</option>
                      </select>
                  </div>
                
                  {{-- Seletor de Tipo de Regra --}}
                  <div>
                    <label class="block text-gray-300 mb-2">Tipo de Regra</label>
                    <select name="condition_type" class="w-full p-3 rounded-md bg-gray-700 border border-gray-600 text-white">
                        <option value="isp">ISP</option>
                        <option value="ip">IP</option>
                        <option value="asn">ASN</option>
                        <option value="user-agent">User-Agent</option>
                        <option value="reverse-dns">Reverse DNS</option> <!-- Nova opção adicionada -->
                    </select>
                </div>                
                
                  {{-- Campo para Inserir o Valor --}}
                  <div x-data="ruleHandler()" class="space-y-4">
                      <label class="block text-gray-300 mb-2">Valores</label>
                  
                      {{-- Caixa de Input Simulada --}}
                      <div class="flex flex-wrap p-2 border border-gray-600 bg-gray-700 w-full min-h-[50px] rounded-md text-white">
                          <textarea x-model="newValue"
                                    @keydown.enter.prevent="addValues()"
                                    @input="splitValues()"
                                    class="bg-transparent border-none outline-none flex-1 p-2 text-white text-lg resize-none"
                                    placeholder="Digite valores e pressione Enter ou use vírgulas..."></textarea>
                      </div>
                  
                      {{-- Lista de Valores Adicionados --}}
                      <template x-if="values.length > 0">
                          <div class="flex flex-wrap gap-2">
                              <template x-for="(value, index) in values" :key="index">
                                  <div class="bg-gray-600 text-white px-3 py-1 rounded-full flex items-center gap-2">
                                      <span x-text="value"></span>
                                      <button type="button" @click="removeValue(index)" class="text-red-400 font-bold hover:text-red-600">&times;</button>
                                  </div>
                              </template>
                          </div>
                      </template>
                  
                      <input type="hidden" name="values" :value="JSON.stringify(values)">
                  </div>
                  
                  <script>
                      function ruleHandler() {
                          return {
                              newValue: '',
                              values: [],
                  
                              // Adicionar múltiplos valores ao pressionar Enter
                              addValues() {
                                  this.splitValues();
                                  this.newValue = ''; // Limpa o campo
                              },
                  
                              // Verifica se há vírgulas ou múltiplas linhas e adiciona os valores separadamente
                              splitValues() {
                                  let inputValues = this.newValue.split(/[\n,]+/).map(value => value.trim()).filter(value => value);
                  
                                  inputValues.forEach(value => {
                                      if (value && !this.values.includes(value)) {
                                          this.values.push(value);
                                      }
                                  });
                  
                                  this.newValue = ''; // Limpa o campo
                              },
                  
                              // Remover valor da lista
                              removeValue(index) {
                                  this.values.splice(index, 1);
                              }
                          };
                      }
                  </script>                                    
                  
                  {{-- Botão de Adicionar Regra --}}
                  <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-3 px-6 rounded-md text-lg font-semibold">
                      Adicionar Regra
                  </button>
              </form>              

            <hr class="my-6 border-gray-600">

            {{-- Tabela Estática com Exemplos --}}
            <div class="overflow-x-auto shadow-lg rounded-lg">
                  <table class="w-full border-collapse table-auto bg-gray-800 text-gray-300">
                      <thead>
                          <tr class="bg-gray-700 text-left">
                              <th class="p-4 border-b border-gray-600 text-sm font-semibold">ID</th>
                              <th class="p-4 border-b border-gray-600 text-sm font-semibold">Ação</th>
                              <th class="p-4 border-b border-gray-600 text-sm font-semibold">Tipo de Condição</th>
                              <th class="p-4 border-b border-gray-600 text-sm font-semibold">Operador</th>
                              <th class="p-4 border-b border-gray-600 text-sm font-semibold">Valores</th>
                              <th class="p-4 border-b border-gray-600 text-sm font-semibold">Ações</th>
                          </tr>
                      </thead>
                      <tbody>
                        @foreach ($rules as $rule)
                              <tr class="hover:bg-gray-700 transition-colors duration-200">
                                    <td class="p-4 border-b border-gray-600">{{ $rule->id }}</td>
                                    <td class="p-4 border-b border-gray-600">{{ ucfirst($rule->action) }}</td>
                                    <td class="p-4 border-b border-gray-600">{{ ucfirst($rule->condition_type) }}</td>
                                    <td class="p-4 border-b border-gray-600">{{ ucfirst($rule->condition_operator) }}</td>
                                    <td class="p-4 border-b border-gray-600">
                                          @php
                                          // Verifique se 'values' já é um array antes de decodificar
                                          $values = is_array($rule->values) ? $rule->values : json_decode($rule->values, true);
                                          @endphp
                                          @if(is_array($values))
                                          @foreach ($values as $value)
                                                <span class="block">{{ $value }}</span>
                                          @endforeach
                                          @else
                                          <span class="block">No values available</span>
                                          @endif
                                    </td>
                                    <td class="p-4 border-b border-gray-600">
                                          <button class="text-red-500 hover:text-red-700 flex items-center gap-1">
                                          <i class="fa-solid fa-trash-alt"></i> Remover
                                          </button>
                                    </td>
                              </tr>
                              @endforeach

                              <!-- Exibir links de navegação para a paginação -->
                              <tr>
                              <td colspan="6" class="p-4 border-t border-gray-600 text-center">
                                    {{ $rules->links() }}
                              </td>
                              </tr>
                      </tbody>
                  </table>
              </div>
              
              
        </div>
    </div>
@endsection
