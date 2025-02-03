@extends('layouts.rabbit.app')

@section('title', 'Campaign Create')

@section('content')


<div class="flex items-center flex-col w-full p-10 mt-4">
      <form id="FormData" action="{{ route('store.rabbit.campaign') }}" method="POST" class="w-full" enctype="multipart/form-data">
      @csrf

      <!-- FIRST DIV -->

            <div class="flex justify-between w-full">
                  <div class="flex flex-col">
                        <h1 class="text-2xl font-bold">Campaign</h1>
                        <span class="text-xl">Data for campaign creation</span>
                  </div>

                  <div class="bg-[#323232] rounded-lg flex flex-col w-2/3 p-4">
                        <div>
                          <label class="mb-2 text-lg text-gray-300" for="name">Name*</label>
                          <input class="p-4 rounded-md border border-gray-400 bg-[#323232] w-full mb-6 text-xl" type="text" name="name" id="name" placeholder="Name*">
                        </div>
                        <div>
                              <label class="mb-2 text-lg text-gray-300" for="domain">Domain*</label>
                              <select class="p-4 rounded-md border border-gray-400 w-full bg-[#323232] mb-6 text-xl" name="domain" id="domain" placeholder="Domain">
                                    @foreach ($domains as $domain)
                                          <option value="{{ $domain->id }}">{{ $domain->domain }}</option>
                                    @endforeach
                              </select>
                        </div>
                        <div>
                              <label class="mb-2 text-lg text-gray-300" for="language">Language*</label>
                              <select class="p-4 rounded-md border border-gray-400 w-full bg-[#323232] mb-6 text-xl" name="language" id="language" placeholder="Language">
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
                              <select class="p-4 rounded-md border border-gray-400 w-full bg-[#323232] mb-6 text-xl" name="traffic_source" id="traffic_source" placeholder="traffic_source">
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
                                        <p class="text-gray-300 mt-2" id="upload-message">Arraste e solte um arquivo aqui ou <span class="text-blue-400 font-semibold cursor-pointer">clique para selecionar</span></p>
                                        <p class="text-gray-500 text-sm mt-1">Formatos suportados: HTML & ZIP</p>
                                    </div>
                                </div>
                          </div>  
                          <script>
                              function handleFileUpload() {
                                  const fileInput = document.getElementById('safe_page');
                                  const fileContainer = document.getElementById('file-upload-container');
                                  const uploadText = document.getElementById('upload-text');
                                  const uploadIcon = document.getElementById('upload-icon');
                                  const uploadMessage = document.getElementById('upload-message');
                          
                                  if (fileInput.files.length > 0) {
                                      // Pegar o nome do arquivo
                                      const fileName = fileInput.files[0].name;
                          
                                      // Mudar o estilo do container e o texto
                                      fileContainer.classList.add('border-green-400', 'bg-[#232323]');
                                      fileContainer.classList.remove('border-gray-500');
                          
                                      uploadIcon.classList.replace('fa-cloud-upload-alt', 'fa-check-circle');
                                      uploadIcon.classList.add('text-green-400');
                                      uploadMessage.innerHTML = `Arquivo selecionado: <span class="font-semibold text-white">${fileName}</span>`;
                                  } else {
                                      // Resetar o estilo caso nenhum arquivo tenha sido selecionado
                                      fileContainer.classList.remove('border-green-400', 'bg-[#232323]');
                                      fileContainer.classList.add('border-gray-500');
                          
                                      uploadIcon.classList.replace('fa-check-circle', 'fa-cloud-upload-alt');
                                      uploadIcon.classList.remove('text-green-400');
                                      uploadMessage.innerHTML = 'Arraste e solte um arquivo aqui ou <span class="text-blue-400 font-semibold cursor-pointer">clique para selecionar</span>';
                                  }
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
                        
                        <div id="inputContainer" class="flex flex-wrap p-2 border border-gray-400 bg-[#323232] w-full min-h-[50px] rounded-md text-white">
                            <input x-model="newLink" @keydown.enter.prevent="addLink()" type="text" class="bg-transparent border-none outline-none flex-1 p-2 text-white text-lg" placeholder="Insert Links*">
                            
                            <!-- Adicione os inputs hidden para cada link -->
                            <template x-for="(link, index) in linksArray" :key="index">
                                <input type="hidden" name="offer_pages[]" :value="link">
                            </template>
                        </div>
                    
                        <template x-if="linksArray.length > 0">
                            <div class="mt-4">
                                <h3 class="text-lg text-gray-300">Links adicionados:</h3>
                                <div class="flex flex-wrap">
                                    <template x-for="(link, index) in linksArray" :key="index">
                                        <div class="bg-gray-400 text-white px-3 py-1 rounded-full flex items-center gap-2 m-1">
                                            <span x-text="link"></span>
                                            <button @click="removeLink(index)" class="text-white font-bold">&times;</button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                    
              
                      
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
              
              <script>
                  function linksHandler() {
                      return {
                          newLink: '',
                          linksArray: [],
              
                          // Função para adicionar link ao array
                          addLink() {
                              const link = this.newLink.trim(); // Remove espaços em branco

                              if (link && !this.linksArray.includes(link) && this.isValidURL(link)) {
                                    this.linksArray.push(link);
                                    this.newLink = ''; // Limpa o campo de input
                                    console.log('Links Array:', this.linksArray); // Verifique os links
                              } else {
                                    alert('Por favor, insira uma URL válida e única!');
                              }
                              },
                                          
                          // Função para remover um link do array
                          removeLink(index) {
                              this.linksArray.splice(index, 1);
                          },
              
                          // Valida a URL
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
              


             <!-- FOURTH DIV -->
            <div class="p-8"></div>

            <div class="flex justify-between w-full">
                  <div class="flex flex-col">
                        <h1 class="text-2xl font-bold">Target</h1>
                        <span class="text-xl">Define your main target</span>
                  </div>

                  <div x-data="{ selectedCountries: [], selectedDevices: [] }" class="bg-[#323232] rounded-lg flex flex-col w-2/3 p-4">
    
                        <!-- Country Selection -->
                        <div>
                            <label class="mb-2 text-lg text-gray-300" for="countries">Country*</label>
                            <select id="countries" x-model="selectedCountries" multiple 
                                class="p-4 rounded-md border border-gray-400 bg-[#323232] w-full mb-6 text-xl">
                                <option value="AF">Afghanistan</option>
                              <option value="AL">Albania</option>
                              <option value="DZ">Algeria</option>
                              <option value="AD">Andorra</option>
                              <option value="AO">Angola</option>
                              <option value="AR">Argentina</option>
                              <option value="AM">Armenia</option>
                              <option value="AU">Australia</option>
                              <option value="AT">Austria</option>
                              <option value="AZ">Azerbaijan</option>
                              <option value="BH">Bahrain</option>
                              <option value="BD">Bangladesh</option>
                              <option value="BY">Belarus</option>
                              <option value="BE">Belgium</option>
                              <option value="BZ">Belize</option>
                              <option value="BJ">Benin</option>
                              <option value="BO">Bolivia</option>
                              <option value="BA">Bosnia and Herzegovina</option>
                              <option value="BR">Brazil</option>
                              <option value="BG">Bulgaria</option>
                              <option value="CA">Canada</option>
                              <option value="CL">Chile</option>
                              <option value="CN">China</option>
                              <option value="CO">Colombia</option>
                              <option value="CR">Costa Rica</option>
                              <option value="HR">Croatia</option>
                              <option value="CY">Cyprus</option>
                              <option value="CZ">Czech Republic</option>
                              <option value="DK">Denmark</option>
                              <option value="DO">Dominican Republic</option>
                              <option value="EC">Ecuador</option>
                              <option value="EG">Egypt</option>
                              <option value="SV">El Salvador</option>
                              <option value="EE">Estonia</option>
                              <option value="FI">Finland</option>
                              <option value="FR">France</option>
                              <option value="GE">Georgia</option>
                              <option value="DE">Germany</option>
                              <option value="GR">Greece</option>
                              <option value="GT">Guatemala</option>
                              <option value="HN">Honduras</option>
                              <option value="HK">Hong Kong</option>
                              <option value="HU">Hungary</option>
                              <option value="IS">Iceland</option>
                              <option value="IN">India</option>
                              <option value="ID">Indonesia</option>
                              <option value="IE">Ireland</option>
                              <option value="IL">Israel</option>
                              <option value="IT">Italy</option>
                              <option value="JM">Jamaica</option>
                              <option value="JP">Japan</option>
                              <option value="KZ">Kazakhstan</option>
                              <option value="KE">Kenya</option>
                              <option value="KR">South Korea</option>
                              <option value="KW">Kuwait</option>
                              <option value="LV">Latvia</option>
                              <option value="LB">Lebanon</option>
                              <option value="LT">Lithuania</option>
                              <option value="LU">Luxembourg</option>
                              <option value="MY">Malaysia</option>
                              <option value="MX">Mexico</option>
                              <option value="MA">Morocco</option>
                              <option value="NL">Netherlands</option>
                              <option value="NZ">New Zealand</option>
                              <option value="NI">Nicaragua</option>
                              <option value="NG">Nigeria</option>
                              <option value="NO">Norway</option>
                              <option value="OM">Oman</option>
                              <option value="PK">Pakistan</option>
                              <option value="PA">Panama</option>
                              <option value="PY">Paraguay</option>
                              <option value="PE">Peru</option>
                              <option value="PH">Philippines</option>
                              <option value="PL">Poland</option>
                              <option value="PT">Portugal</option>
                              <option value="QA">Qatar</option>
                              <option value="RO">Romania</option>
                              <option value="RU">Russia</option>
                              <option value="SA">Saudi Arabia</option>
                              <option value="RS">Serbia</option>
                              <option value="SG">Singapore</option>
                              <option value="SK">Slovakia</option>
                              <option value="SI">Slovenia</option>
                              <option value="ZA">South Africa</option>
                              <option value="ES">Spain</option>
                              <option value="LK">Sri Lanka</option>
                              <option value="SE">Sweden</option>
                              <option value="CH">Switzerland</option>
                              <option value="TW">Taiwan</option>
                              <option value="TH">Thailand</option>
                              <option value="TR">Turkey</option>
                              <option value="UA">Ukraine</option>
                              <option value="AE">United Arab Emirates</option>
                              <option value="GB">United Kingdom</option>
                              <option value="US">United States</option>
                              <option value="UY">Uruguay</option>
                              <option value="VE">Venezuela</option>
                              <option value="VN">Vietnam</option>
                            </select>
                        </div>
                    
                        <!-- Device Selection -->
                        <div>
                            <label class="mb-2 text-lg text-gray-300" for="devices">Device*</label>
                            <select id="devices" x-model="selectedDevices" multiple 
                                class="p-4 rounded-md border border-gray-400 bg-[#323232] w-full mb-6 text-xl">
                                <option value="PC">Desktop</option>
                                <option value="TEL">Mobile</option>
                                <option value="TAB">Tablet</option>
                                <option value="TV">Smart TV</option>
                            </select>
                        </div>
                    
                        <!-- Campos ocultos para enviar os dados -->
                        <template x-for="(country, index) in selectedCountries" :key="index">
                            <input type="hidden" name="target_countries[]" x-bind:value="country">
                        </template>
                    
                        <template x-for="(device, index) in selectedDevices" :key="index">
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
