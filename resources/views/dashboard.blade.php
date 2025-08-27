<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Dashboard
                </h2>
                <p class="text-gray-600 mt-1">Gerencie seus links encurtados</p>
            </div>
            <div class="flex space-x-3">
                <select id="period-filter" class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm text-sm px-3 py-2">
                    <option value="1" {{ $period == '1' ? 'selected' : '' }}>Hoje</option>
                    <option value="7" {{ $period == '7' ? 'selected' : '' }}>7 dias</option>
                    <option value="30" {{ $period == '30' ? 'selected' : '' }}>30 dias</option>
                </select>
                <button id="refresh-btn" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm border">
                    🔄 Atualizar
                </button>
                <a href="{{ route('links.index') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-2 rounded-lg font-medium shadow-lg transition duration-200">
                    ➕ Criar Link
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
            <div id="metrics-container" class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 overflow-hidden shadow-lg rounded-lg text-white">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <div class="text-blue-100 text-sm font-medium">Total de Links</div>
                                <div class="text-3xl font-bold" id="total-links">{{ $totalLinks }}</div>
                            </div>
                            <div class="text-blue-200 text-2xl">🔗</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-500 to-green-600 overflow-hidden shadow-lg rounded-lg text-white">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <div class="text-green-100 text-sm font-medium">Links Ativos</div>
                                <div class="text-3xl font-bold" id="active-links">{{ $activeLinks }}</div>
                            </div>
                            <div class="text-green-200 text-2xl">✅</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-red-500 to-red-600 overflow-hidden shadow-lg rounded-lg text-white">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <div class="text-red-100 text-sm font-medium">Links Expirados</div>
                                <div class="text-3xl font-bold" id="expired-links">{{ $expiredLinks }}</div>
                            </div>
                            <div class="text-red-200 text-2xl">❌</div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 overflow-hidden shadow-lg rounded-lg text-white">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <div class="text-yellow-100 text-sm font-medium">Links Inativos</div>
                                <div class="text-3xl font-bold" id="inactive-links">{{ $inactiveLinks ?? 0 }}</div>
                            </div>
                            <div class="text-yellow-200 text-2xl">⏸️</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 overflow-hidden shadow-lg rounded-lg text-white">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <div class="text-purple-100 text-sm font-medium">Total de Cliques</div>
                                <div class="text-3xl font-bold" id="total-clicks">{{ $totalClicks }}</div>
                            </div>
                            <div class="text-purple-200 text-2xl">👆</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Top 10 Links</h3>
                        <span class="text-xs text-gray-500">Atualizado há <span id="last-update">agora</span></span>
                    </div>
                    <div id="top-links-container">
                        @if($topLinks->count() > 0)
                            <div class="space-y-3">
                                @foreach($topLinks as $index => $link)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="flex items-center space-x-3 flex-1">
                                            <div class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-sm font-medium text-gray-900 truncate">
                                                    {{ Str::limit($link->original_url, 50) }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ url('/s/' . $link->slug) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $link->status === 'active' ? 'bg-green-100 text-green-800' : 
                                                   ($link->status === 'expired' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($link->status) }}
                                            </span>
                                            <div class="text-lg font-bold text-blue-600">
                                                {{ $link->click_count }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-gray-400 text-4xl mb-3">📊</div>
                                <p class="text-gray-500">Nenhum link criado ainda.</p>
                                <a href="{{ route('links.index') }}" class="mt-2 inline-block text-blue-600 hover:text-blue-800">
                                    Criar primeiro link
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ação Rápida</h3>
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-6 border border-blue-100">
                        <div class="text-center">
                            <div class="mb-4">
                                <span class="text-4xl">🚀</span>
                            </div>
                            <h4 class="text-xl font-bold text-gray-900 mb-2">Criar Novo Link</h4>
                            <p class="text-gray-600 mb-4">Encurte uma URL e gere um QR Code instantaneamente</p>
                            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                <a href="{{ route('links.index') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-semibold py-3 px-8 rounded-lg shadow-lg transition duration-200 transform hover:scale-105">
                                    ➕ Criar Link Agora
                                </a>
                                <a href="{{ route('links.index') }}" class="bg-white border border-gray-300 hover:border-gray-400 text-gray-700 font-medium py-3 px-6 rounded-lg transition duration-200">
                                    📋 Ver Todos os Links
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateMetrics() {
            const period = document.getElementById('period-filter').value;
            
            fetch(`/metrics/summary?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-links').textContent = data.total_links;
                    document.getElementById('active-links').textContent = data.active_links;
                    document.getElementById('expired-links').textContent = data.expired_links;
                    document.getElementById('inactive-links').textContent = data.inactive_links;
                    document.getElementById('total-clicks').textContent = data.total_clicks;
                    document.getElementById('last-update').textContent = 'agora';
                });

            fetch(`/metrics/top?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    updateTopLinks(data.links);
                });
        }

        function updateTopLinks(links) {
            const container = document.getElementById('top-links-container');
            if (links.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-4xl mb-3">📊</div>
                        <p class="text-gray-500">Nenhum link criado ainda.</p>
                        <a href="/links" class="mt-2 inline-block text-blue-600 hover:text-blue-800">
                            Criar primeiro link
                        </a>
                    </div>
                `;
                return;
            }

            const html = links.map((link, index) => {
                const statusClass = link.status === 'active' ? 'bg-green-100 text-green-800' : 
                                   (link.status === 'expired' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800');
                return `
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-3 flex-1">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                ${index + 1}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900 truncate">
                                    ${link.original_url.length > 50 ? link.original_url.substring(0, 50) + '...' : link.original_url}
                                </div>
                                <div class="text-xs text-gray-500">
                                    ${window.location.origin}/s/${link.slug}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass}">
                                ${link.status.charAt(0).toUpperCase() + link.status.slice(1)}
                            </span>
                            <div class="text-lg font-bold text-blue-600">
                                ${link.click_count}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
            
            container.innerHTML = `<div class="space-y-3">${html}</div>`;
        }

        document.getElementById('period-filter').addEventListener('change', updateMetrics);
        document.getElementById('refresh-btn').addEventListener('click', updateMetrics);

        setInterval(updateMetrics, 5000);
    </script>
</x-app-layout>
