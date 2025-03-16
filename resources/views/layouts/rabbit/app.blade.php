<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

     <!-- Scripts -->
     @vite(['resources/css/app.css', 'resources/js/app.js'])

     <!-- Styles -->
     @livewireStyles

</head>
<body class="bg-[#1e1625] text-white">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-[#280037] p-4 flex flex-col fixed top-0 left-0 h-screen overflow-y-auto">
            <div class="w-full flex justify-center">
                <img class="w-32" src="/images/logo_nome_light_2.png">
            </div>            
            <nav class="mt-4 space-y-2">
                <a href="{{ route('dashboard') }}" 
                   class="flex gap-8 items-center p-2 space-x-2 @if(request()->routeIs('dashboard')) bg-gray-300 bg-opacity-20 @else hover:bg-gray-300 hover:bg-opacity-20 @endif rounded">
                    <i class="fas fa-home text-xl text-gray-300"></i>
                    <span>Dashboard</span>
                </a>
            
                <a href="{{ route('view.rabbit.domains') }}" 
                   class="flex gap-8 items-center p-2 space-x-2 @if(request()->routeIs('view.rabbit.domains')) bg-gray-300 bg-opacity-20 @else hover:bg-gray-300 hover:bg-opacity-20 @endif rounded">
                    <i class="fas fa-globe text-xl text-gray-300"></i>
                    <span>Domains</span>
                </a>
            
                <a href="{{ route('view.rabbit.campaigns') }}" 
                   class="flex gap-8 items-center p-2 space-x-2 @if(request()->routeIs('view.rabbit.campaigns')) bg-gray-300 bg-opacity-20 @else hover:bg-gray-300 hover:bg-opacity-20 @endif rounded">
                    <i class="fas fa-bullhorn text-xl text-gray-300"></i>
                    <span>Campaigns</span>
                </a>
            
                <a href="{{ route('view.rabbit.requests') }}" 
                   class="flex gap-8 items-center p-2 space-x-2 @if(request()->routeIs('view.rabbit.requests')) bg-gray-300 bg-opacity-20 @else hover:bg-gray-300 hover:bg-opacity-20 @endif rounded">
                    <i class="fas fa-file-alt text-xl text-gray-300"></i>
                    <span>Requests</span>
                </a>
            
                <a href="{{ route('view.rabbit.rules') }}" 
                   class="flex gap-8 items-center p-2 space-x-2 @if(request()->routeIs('view.rabbit.rules')) bg-gray-300 bg-opacity-20 @else hover:bg-gray-300 hover:bg-opacity-20 @endif rounded">
                    <i class="fas fa-shield-alt text-xl text-gray-300"></i>
                    <span>Regras</span>
                </a>
            </nav>
            
            
            
            
        
        </aside>
        
        <main class="flex-1 p-6 ml-64">
        <div class="flex justify-between items-center">
                @if(request()->routeIs('view.rabbit.campaign'))
                <button class="px-4 py-2 rounded-2xl text-purple-500 font-bold text-lg items-center flex"><i class="fa-solid fa-arrow-left text-2xl"></i></button>
                @endif
                <h2 class="text-2xl font-bold">@yield('title')</h2>
                @if(request()->routeIs('view.rabbit.campaign'))
                <button  id="SubmitForm" class="bg-purple-600 px-4 py-2 rounded-2xl text-white font-bold text-lg items-center flex"><i class="fa-regular fa-floppy-disk mr-2 text-2xl"></i> SAVE</button>
                @elseif(request()->routeIs('edit.rabbit.campaign'))
                <button  id="SubmitForm" class="bg-purple-600 px-4 py-2 rounded-2xl text-white font-bold text-lg items-center flex"><i class="fa-regular fa-floppy-disk mr-2 text-2xl"></i> SAVE</button>
                @elseif(request()->routeIs('view.rabbit.domains'))
                <div x-data="{ showModal: false }">
                    <!-- Botão para abrir o modal -->
                    <button @click="showModal = true"
                        class="bg-purple-600 px-4 py-2 rounded-2xl text-white font-bold text-lg flex items-center">
                        <i class="fa-regular fa-floppy-disk mr-2 text-2xl"></i> CREATE +
                    </button>

                    <div x-show="showModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
                        <!-- Modal Container -->
                    <div x-data="{ 
                        domain: '', 
                        saveDomain() { 
                            axios.post('{{ route('store.rabbit.domain') }}', { domain: this.domain })
                                .then(response => {
                                    alert(response.data.message); // Exibe mensagem de sucesso
                                    this.domain = ''; // Limpa o campo após salvar
                                })
                                .catch(error => {
                                    alert(error.response.data.message || 'Something went wrong!');
                                });
                        } 
                    }"  class="bg-gray-900 text-white rounded-lg shadow-lg w-[550px] p-6 relative">
                            <!-- Modal Header -->
                            <div class="flex justify-between items-center border-b border-gray-700 pb-3">
                                <h2 class="text-2xl font-bold">Domains</h2>
                                <button @click="showModal = false" class="text-gray-400 hover:text-red-500 text-2xl">&times;</button>
                            </div>
                
                            <!-- Modal Body -->
                            <div class="mt-4">
                                <label for="domain" class="text-lg">Domain</label>
                                <input type="text" name="domain" id="domain" x-model="domain" placeholder="example: https://www.domain.com"
                                class="w-full mt-2 p-3 rounded-md bg-zinc-800 border border-gray-600 text-white">                            
                                
                                <small class="block mt-1 text-gray-400">Add and verify the domains.</small>
                
                                <!-- Informações adicionais -->
                                <div class="mt-4 space-y-3">
                                    <div class="bg-zinc-700 p-3 rounded-lg">
                                        <p>1: Point your domain to Cloudflare nameservers.</p>
                                    </div>
                                    <div class="bg-zinc-700 p-3 rounded-lg">
                                        <p>2: Visit your domain's DNS records panel on Cloudflare.</p>
                                    </div>
                                    <div class="bg-zinc-700 p-3 rounded-lg">
                                        <p>3: Create a A record with the value:</p>
                                        <span class="bg-gray-600 p-1 rounded">@</span>
                                        <p class="mt-1">Pointing to:</p>
                                        <strong class="cursor-pointer text-pink-400">{{ env('IP_URL') }}</strong>
                                    </div>
                                    <div class="bg-zinc-700 p-3 rounded-lg">
                                        <p>4: Save the DNS</p>
                                    </div>
                                </div>
                            </div>
                
                            <!-- Modal Footer -->
                            <div class="mt-6 flex justify-end">
                                <button @click="showModal = false" class="px-4 py-2 bg-gray-600 rounded-lg text-white mr-3">Cancel</button>
                                <button @click="saveDomain()" class="px-4 py-2 bg-pink-500 rounded-lg text-white font-bold">Save</button>
                            </div>
                        </div>
                    </div>
                </div>

                @else
                <a href="{{ route('view.rabbit.campaign') }}" class="bg-purple-600 px-4 py-2 rounded-2xl text-white font-bold">CREATE +</a>
                @endif
        </div>  
       
        @yield('content')

        </main>
    </div>

    @stack('modals')

    @livewireScripts
</body>
</html>
