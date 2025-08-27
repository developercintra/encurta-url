<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Detalhes do Link
            </h2>
            <a href="{{ route('links.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg">
                ← Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $link->status === 'active' ? 'bg-green-100 text-green-800' : 
                                   ($link->status === 'expired' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($link->status) }}
                            </span>
                            <span class="text-sm text-gray-500">
                                Criado em {{ $link->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('links.qrcode', $link) }}" target="_blank"
                               class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium">
                                📱 Ver QR Code
                            </a>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Link</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">URL Original</label>
                                    <div class="flex items-center space-x-2">
                                        <input type="text" value="{{ $link->original_url }}" readonly
                                               class="flex-1 border-gray-300 rounded-md bg-gray-50 text-sm">
                                        <button onclick="copyToClipboard('{{ $link->original_url }}')"
                                                class="text-gray-500 hover:text-gray-700">📋</button>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">URL Curta</label>
                                    <div class="flex items-center space-x-2">
                                        <input type="text" value="{{ url('/s/' . $link->slug) }}" readonly
                                               class="flex-1 border-gray-300 rounded-md bg-gray-50 text-sm">
                                        <button onclick="copyToClipboard('{{ url('/s/' . $link->slug) }}')"
                                                class="text-gray-500 hover:text-gray-700">📋</button>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                                    <input type="text" value="{{ $link->slug }}" readonly
                                           class="w-full border-gray-300 rounded-md bg-gray-50 text-sm">
                                </div>

                                @if($link->expires_at)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Data de Expiração</label>
                                        <input type="text" value="{{ $link->expires_at->format('d/m/Y H:i') }}" readonly
                                               class="w-full border-gray-300 rounded-md bg-gray-50 text-sm">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Estatísticas</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-blue-50 p-4 rounded-lg text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ $link->click_count }}</div>
                                    <div class="text-sm text-blue-700">Total de Cliques</div>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ $link->visits()->count() }}</div>
                                    <div class="text-sm text-green-700">Visitas Registradas</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($link->visits()->count() > 0)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Últimas Visitas</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Data e Hora
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            User Agent
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($link->visits()->orderBy('created_at', 'desc')->take(20)->get() as $visit)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $visit->created_at->format('d/m/Y H:i:s') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ Str::limit($visit->user_agent, 80) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($link->visits()->count() > 20)
                            <div class="mt-4 text-center">
                                <span class="text-sm text-gray-500">
                                    Mostrando 20 de {{ $link->visits()->count() }} visitas
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">QR Code</h3>
                    <div class="text-center">
                        <img src="{{ route('links.qrcode', $link) }}" alt="QR Code" class="mx-auto border rounded-lg">
                        <p class="mt-3 text-sm text-gray-600">
                            Escaneie este QR Code para acessar o link
                        </p>
                        <a href="{{ route('links.qrcode', $link) }}" download="qrcode-{{ $link->slug }}.png"
                           class="mt-2 inline-block bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">
                            💾 Baixar QR Code
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                const notification = document.createElement('div');
                notification.textContent = 'Copiado para a área de transferência!';
                notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                document.body.appendChild(notification);
                setTimeout(() => notification.remove(), 2000);
            });
        }
    </script>
</x-app-layout>