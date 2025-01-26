<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Denunciar Website') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Mensagem de sucesso -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Formulário de Denúncia -->
            <div class="bg-white shadow-xl sm:rounded-lg p-8">
                <h3 class="text-2xl font-bold mb-6">Denunciar um Website</h3>
                <form method="POST" action="{{ route('email.send') }}">
                    @csrf
                    <!-- Seleção do Website -->
                    <div class="mb-6">
                        <label for="domain" class="block text-gray-700 font-medium mb-2">Escolha o Website:</label>
                        <input type="text" name="domain" id="domain" placeholder="exemplo.com"
                        class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                        required>
                    </div>

                    <div class="mb-6">
                        <label for="abuse" class="block text-gray-700 font-medium mb-2">Abuse Email:</label>
                        <input type="text" name="abuse" id="abuse" placeholder="abuse@example.com"
                        class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                        required>
                    </div>

                    <div class="mb-6">
                        <label for="brand" class="block text-gray-700 font-medium mb-2">Marca:</label>
                        <input type="text" name="brand" id="brand" placeholder="Nome Da Marca"
                        class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                        required>
                    </div>

                    <!-- Motivo da Denúncia -->
                    <div class="mb-6">
                        <label for="reason" class="block text-gray-700 font-medium mb-2">Motivo da Denúncia:</label>
                        <textarea name="reason" id="reason" rows="4" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                            Reporter's Name: Diego Daminelli Lopes
                            Reporter's Email Address: ip-violations-report@brandmonitor.com.br
                            Reporter's Company Name: BRANDMONITOR SERVICOS ONLINE LTDA
                            Reporter's Telephone Number: 21999185056

                            Reported URLs:
                            {site_url}
                            
                            Original Work: Trademarked Symbol: {brand}
                            Registration Number: 900411724, 821285815
                            Registration Office: INPI
                            Logs or Evidence of Abuse: The reported website improperly uses the “{brand}” logo on its navigation bar and features a design that misleadingly suggests it is the official site, potentially confusing users. Additionally, it offers products at significantly low prices, engaging in fraudulent activities by selling without delivering them to buyers.
                            
                            Fraudulent website:  {site_url}
                            We declare that the official website of “{brand}” is https://www.kikocosmetics.com/pt-pt/, and no other online channel is officially associated with the brand.
                            Please note that {brand} is duly registered with INPI: 900411724, 821285815 "</textarea>
                    </div>

                    <!-- Botão de Envio -->
                    <div class="flex justify-end">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200">
                            Enviar Denúncia
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Mensagem de sucesso -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Formulário de Configuração SMTP -->
            <div class="bg-white shadow-xl sm:rounded-lg p-8">
                <h3 class="text-2xl font-bold mb-6">Cadastrar Configurações SMTP</h3>
                <form method="POST" action="{{ route('email.config.store') }}">
                    @csrf
                    <!-- Endereço de E-mail -->
                    <div class="mb-6">
                        <label for="email" class="block text-gray-700 font-medium mb-2">Endereço de E-mail:</label>
                        <input type="text" name="email" id="email" placeholder="seu-email@dominio.com"
                               class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                    </div>
                
                    <!-- Botão de Envio -->
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200">
                            Salvar Configurações
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    <div class="mt-12">
        <h3 class="text-2xl font-bold mb-6">Configurações SMTP Cadastradas</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm">
                        <th class="py-3 px-4 text-left">Endereço de E-mail</th>
                        <th class="py-3 px-4 text-left">Servidor SMTP</th>
                        <th class="py-3 px-4 text-left">Porta</th>
                        <th class="py-3 px-4 text-left">Usuário SMTP</th>
                        <th class="py-3 px-4 text-left">Criptografia</th>
                        <th class="py-3 px-4 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($configs as $config)
                        <tr class="border-b">
                            <td class="py-4 px-4">{{ $config->email }}</td>
                            <td class="py-4 px-4">{{ $config->smtp_host }}</td>
                            <td class="py-4 px-4">{{ $config->smtp_port }}</td>
                            <td class="py-4 px-4">{{ $config->smtp_password }}</td>
                            <td class="py-4 px-4">{{ strtoupper($config->smtp_encryption) }}</td>
                            <td class="py-4 px-4 text-center">
                                <form method="POST" action="{{ route('email.config.destroy', $config->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline ml-4" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 px-4 text-center text-gray-500">Nenhuma configuração encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
