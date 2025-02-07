@extends('layouts.rabbit.app')

@section('title', 'Campaign Create')

@section('content')

@php
$campaign->offer_pages = json_decode($campaign->offer_pages, true) ?? [];
@endphp

<div class="flex items-center flex-col w-full p-10 mt-4">
      <form id="FormData" action="{{ route('update.rabbit.campaign') }}" method="POST" class="w-full" enctype="multipart/form-data">
      @csrf

      <!-- FIRST DIV -->
      <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">

            <div class="flex justify-between w-full">
                  <div class="flex flex-col">
                        <h1 class="text-2xl font-bold">Campaign</h1>
                        <span class="text-xl">Data for campaign creation</span>
                  </div>

                  <div class="bg-[#323232] rounded-lg flex flex-col w-2/3 p-4">
                        <div>
                          <label class="mb-2 text-lg text-gray-300" for="name">Name*</label>
                          <input class="p-4 rounded-md border border-gray-400 bg-[#323232] w-full mb-6 text-xl" type="text" name="name" value="{{ $campaign->name }}" id="name" placeholder="Name*">
                        </div>
                        <div>
                              <label class="mb-2 text-lg text-gray-300" for="domain">Domain*</label>
                              <select disabled class="p-4 rounded-md border border-gray-400 w-full bg-[#323232] mb-6 text-xl" name="domain" id="domain" placeholder="Domain">
                                    <option value="{{ $campaign->domain->id }}">{{ $campaign->domain->domain }}</option>
                              </select>
                        </div>
                        <div>
                              <label class="mb-2 text-lg text-gray-300" for="language">Language*</label>
                              <select disabled class="p-4 rounded-md border border-gray-400 w-full bg-[#323232] mb-6 text-xl" name="language" id="language" placeholder="Language">
                                    <option value="pt-BR">Portuguese</option>
                                    <option value="en-US">English</option>
                                    <option value="es-ES">Spanish</option>
                                    <option value="fr-FR">French</option>
                                    <option value="de-DE">German</option>
                                    <option value="it-IT">Italian</option>
                                    <option value="ja-JP">Japanese</option>
                                    <option value="zh-CN">Chinese</option>
                                    <option value="ru-RU">Russian</option>
                                    <option value="ar-SA">Arabic</option>
                                    <option value="ko-KR">Korean</option>
                              </select>
                        </div>
                        <div>
                              <label class="mb-2 text-lg text-gray-300" for="traffic_source">Traffic Source*</label>
                              <select disabled class="p-4 rounded-md border border-gray-400 w-full bg-[#323232] mb-6 text-xl" name="traffic_source" id="traffic_source" placeholder="traffic_source">
                                    <option value="G-DEMAND">Google-Demand</option>
                                    <option value="G-SEARCH">Google-Search</option>
                                    <option value="G-YOUTUBE">Google-Youtube</option>
                              </select>
                        </div>
                  </div>
            </div>

            <!-- SECOND DIV -->
            <div class="p-8"></div>

            <div class="flex justify-between w-full">
                  <div class="flex flex-col">
                        <h1 class="text-2xl font-bold">Safe Page</h1>
                        <span class="text-xl">The Page that gonna protect your offer page</span>
                  </div>

                  <div class="bg-[#323232] rounded-lg flex flex-col w-2/3 p-4">
                    <div>
                        <label class="mb-2 text-lg text-gray-300 block" for="safe_page">Safe Page*</label>
                        <div class="relative flex items-center justify-center w-full border-2 border-dashed border-gray-500 rounded-lg p-6 bg-[#1E1E1E] hover:border-gray-300 transition" id="file-upload-container">
                            <input type="file" name="safe_page" id="safe_page" 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                                   accept=".zip,.html" onchange="handleFileUpload()">
                            
                            <div class="text-center" id="upload-text">
                                <i class="fa-solid fa-cloud-upload-alt text-4xl text-gray-400" id="upload-icon"></i>
                                <p class="text-gray-300 mt-2" id="upload-message">
                                    Arraste e solte um arquivo aqui ou <span class="text-blue-400 font-semibold cursor-pointer">clique para selecionar</span>
                                </p>
                                <p class="text-gray-500 text-sm mt-1">Formatos suportados: HTML & ZIP</p>
                            </div>
                        </div>
                        <p id="file-url" class="text-gray-300 text-sm mt-2"></p>
                    </div>
                    
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const safePagePath = "{{ $campaign->safe_page ?? '' }}"; // Substituir pelo valor real do backend
                    
                            if (safePagePath) {
                                markFileAsUploaded(safePagePath);
                            }
                        });
                    
                        function handleFileUpload() {
                            const fileInput = document.getElementById('safe_page');
                            if (fileInput.files.length > 0) {
                                const fileName = fileInput.files[0].name;
                                const safePagePath = `campaign/HtjXQPlFen/${fileName}`; // Ajustar se necessário
                                markFileAsUploaded(safePagePath);
                            }
                        }
                    
                        function markFileAsUploaded(filePath) {
                            const fileContainer = document.getElementById('file-upload-container');
                            const uploadIcon = document.getElementById('upload-icon');
                            const uploadMessage = document.getElementById('upload-message');
                            const fileUrl = document.getElementById('file-url');
                    
                            const fullUrl = window.location.origin + "/" + filePath;
                    
                            // Atualiza estilos e mensagens
                            fileContainer.classList.add('border-green-400', 'bg-[#232323]');
                            fileContainer.classList.remove('border-gray-500');
                    
                            uploadIcon.classList.replace('fa-cloud-upload-alt', 'fa-check-circle');
                            uploadIcon.classList.add('text-green-400');
                            uploadMessage.innerHTML = `Arquivo carregado: <span class="font-semibold text-white">${filePath.split('/').pop()}</span>`;
                    
                            // Exibir a URL do arquivo
                            fileUrl.innerHTML = `URL do arquivo: <a href="${fullUrl}" target="_blank" class="text-blue-400">${fullUrl}</a>`;
                        }
                    </script>
                                  
                        <div>
                              <label class="mb-4 text-lg text-gray-300" for="method_safe">Method*</label>
                              <div class="flex items-center space-x-4">
                                  <!-- TWR Redirect Option -->
                                  <div class="flex items-center space-x-2">
                                      <input type="radio" checked name="method_safe" id="method_safe_twr" value="twr_redirect" class="w-5 h-5 text-blue-500 border-4 border-white bg-[#323232] rounded-full">
                                      <label for="method_safe_twr" class="text-gray-300 text-lg">TWR Redirect</label>
                                  </div>
                                  
                                  <!-- Pre-sell Option -->
                                  <div class="flex items-center space-x-2">
                                      <input type="radio" name="method_safe" id="method_safe_presell" value="pre_sell" class="w-5 h-5 text-blue-500 border-4 border-white bg-[#323232] rounded-full">
                                      <label for="method_safe_presell" class="text-gray-300 text-lg">Pre-sell</label>
                                  </div>
                              </div>
                          </div>
                  </div>
            </div>


             <!-- THIRD DIV -->
             <div class="p-8"></div>

             <div class="flex justify-between w-full">
                  <div class="flex flex-col">
                      <h1 class="text-2xl font-bold">Offer Pages</h1>
                      <span class="text-xl">Put your offer pages here</span>
                  </div>
              
                  <div class="bg-[#323232] rounded-lg flex flex-col w-2/3 p-4">
                      <div class="mb-4">
                          <div class="flex items-center space-x-4">
                              <!-- TWR Redirect Option -->
                              <div class="flex items-center space-x-2">
                                  <input type="radio" checked name="single_offer" id="single_offer" value="twr_redirect" class="w-5 h-5 text-blue-500 border-4 border-white bg-[#323232] rounded-full">
                                  <label for="single_offer" class="text-gray-300 text-lg">Single Offer</label>
                              </div>
                          </div>
                      </div>
              
                      <div x-data="linksHandler()" class="relative mb-6">
                        <label class="mb-2 text-lg text-gray-300" for="offer_pages">Links*</label>
                    
                        <!-- Container do input e dos chips -->
                        <div id="inputContainer" class="flex flex-wrap p-2 border border-gray-400 bg-[#323232] w-full min-h-[50px] rounded-md text-white">
                            <input x-model="newLink" @keydown.enter.prevent="addLink()" type="text"
                                   class="bg-transparent border-none outline-none flex-1 p-2 text-white text-lg"
                                   placeholder="Insira um link e pressione Enter">
                    
                            <!-- Inputs ocultos para enviar os links no formulário -->
                            <template x-for="(link, index) in linksArray" :key="index">
                                <input type="hidden" name="offer_pages[]" :value="link">
                            </template>
                        </div>
                    
                        <!-- Exibir links adicionados -->
                        <div class="mt-4" x-show="linksArray.length > 0">
                            <h3 class="text-lg text-gray-300">Links adicionados:</h3>
                            <div class="flex flex-wrap">
                                <template x-for="(link, index) in linksArray" :key="index">
                                    <div class="bg-gray-400 text-white px-3 py-1 rounded-full flex items-center gap-2 m-1">
                                        <span x-text="link"></span>
                                        <button type="button" class="text-white font-bold" @click="removeLink(index)">&times;</button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        function linksHandler() {
                            return {
                                newLink: '',
                                linksArray: @js($campaign->offer_pages ?? []),  // Corrigido para não aplicar json_decode
                    
                                // Adicionar novo link ao array
                                addLink() {
                                    const link = this.newLink.trim(); 
                    
                                    if (link && !this.linksArray.includes(link) && this.isValidURL(link)) {
                                        this.linksArray.push(link);
                                        this.newLink = ''; 
                                    } else {
                                        alert('Por favor, insira uma URL válida e única!');
                                    }
                                },
                    
                                // Remover link
                                removeLink(index) {
                                    this.linksArray.splice(index, 1);
                                },
                    
                                // Valida URL
                                isValidURL(url) {
                                    const pattern = new RegExp('^(https?:\\/\\/)?' +
                                        '((([a-zA-Z0-9-]+\\.)+[a-zA-Z]{2,})|' + 
                                        'localhost|' + 
                                        '\\d{1,3}(\\.\\d{1,3}){3})' + 
                                        '(\\:\\d+)?(\\/[-a-zA-Z0-9@:%._\\+~#=]*)*' + 
                                        '(\\?[;&a-zA-Z0-9%_.~+=-]*)?' + 
                                        '(\\#[-a-zA-Z0-9_]*)?$', 'i');
                                    return pattern.test(url);
                                }
                            };
                        }
                    </script>                    
              
                      
                      <!-- Método de oferta -->
                      <div>
                          <label class="mb-4 text-lg text-gray-300" for="method_safe">Method*</label>
                          <div class="flex items-center space-x-4">
                              <div class="flex items-center space-x-2">
                                  <input type="radio" checked name="method_offer" id="method_offer" value="method_offer_twr_redirect" class="w-5 h-5 text-blue-500 border-4 border-white bg-[#323232] rounded-full">
                                  <label for="method_offer" class="text-gray-300 text-lg">TWR Redirect</label>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>          


             <!-- FOURTH DIV -->
            <div class="p-8"></div>

            <div class="flex justify-between w-full">
                  <div class="flex flex-col">
                        <h1 class="text-2xl font-bold">Target</h1>
                        <span class="text-xl">Define your main target</span>
                  </div>

                  <div x-data="{
                    selectedCountries: @json(json_decode($campaign->target_countries) ?? []),
                    selectedDevices: @json(json_decode($campaign->target_devices) ?? [])
                }" 
                class="bg-[#323232] rounded-lg flex flex-col w-2/3 p-4">            
                    
                    <!-- Country Selection -->
                    <div>
                        <label class="mb-2 text-lg text-gray-300" for="countries">Country*</label>
                        <select disabled id="countries" x-model="selectedCountries" multiple 
                            class="p-4 rounded-md border border-gray-400 bg-[#323232] w-full mb-6 text-xl">
                            @foreach(["AF" => "Afghanistan", "AL" => "Albania", "AD" => "Andorra", "BR" => "Brazil", "US" => "United States"] as $code => $name)
                                <option value="{{ $code }}" x-bind:selected="selectedCountries.includes('{{ $code }}')">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Device Selection -->
                    <div>
                        <label class="mb-2 text-lg text-gray-300" for="devices">Device*</label>
                        <select disabled id="devices" x-model="selectedDevices" multiple 
                            class="p-4 rounded-md border border-gray-400 bg-[#323232] w-full mb-6 text-xl">
                            @foreach(["PC" => "Desktop", "TEL" => "Mobile", "TAB" => "Tablet", "TV" => "Smart TV"] as $code => $name)
                                <option value="{{ $code }}" x-bind:selected="selectedDevices.includes('{{ $code }}')">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Hidden Inputs for Submission -->
                    <template x-for="country in selectedCountries" :key="country">
                        <input type="hidden" name="target_countries[]" x-bind:value="country">
                    </template>
                    
                    <template x-for="device in selectedDevices" :key="device">
                        <input type="hidden" name="target_devices[]" x-bind:value="device">
                    </template>
                    
                    <!-- Debug -->
                    <div class="mt-4 text-gray-300 text-sm">
                        <strong>Selected Countries:</strong> <span x-text="selectedCountries"></span> <br>
                        <strong>Selected Devices:</strong> <span x-text="selectedDevices"></span>
                    </div>
                </div>
                
                    
                    
            </div>

      </form>

      <script>
            // Selecionando o botão e o formulário
            const botaoSalvar = document.getElementById('SubmitForm');
            const formulario = document.getElementById('FormData');
        
            // Adicionando o evento de clique no botão
            botaoSalvar.addEventListener('click', function() {
                // Envia o formulário quando o botão for clicado
                formulario.submit();
            });
        </script>
</div>


@endsection
