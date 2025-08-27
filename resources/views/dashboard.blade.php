<x-app-layout>
    <x-slot name="header">
        <!-- Tailwind + Font -->
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: '#4361ee',
                            secondary: '#3f37c9',
                            success: '#4cc9f0',
                            info: '#4895ef',
                            warning: '#f72585',
                            danger: '#e63946',
                            dark: '#1d3557',
                            light: '#f1faee',
                            background: '#f8fafc'
                        }
                    }
                }
            }
        </script>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

            body {
                font-family: 'Inter', sans-serif;
                background-color: #f5f7fb;
            }

            .card {
                border-radius: 12px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
                transition: all .3s ease;
            }

            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            }

            .metric-card {
                position: relative;
                overflow: hidden;
            }

            .metric-card::before {
                content: '';
                position: absolute;
                top: -20px;
                right: -20px;
                width: 80px;
                height: 80px;
                border-radius: 50%;
                opacity: .1;
                background: currentColor;
            }

            .top-link-item {
                transition: all .2s ease;
                border-left: 4px solid transparent;
            }

            .top-link-item:hover {
                border-left-color: #4361ee;
                background-color: #f8fafc;
            }

            .status-badge {
                font-size: 0.7rem;
                padding: 0.35rem 0.7rem;
                border-radius: 20px;
            }

            .gradient-bg {
                background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            }

            .refresh-btn:hover {
                animation: spin 1s linear;
            }

        
        </style>

        <!-- Header -->
        <div class="gradient-bg text-white shadow-lg rounded-lg px-6 py-5 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">Dashboard</h2>
                <p class="text-blue-100">Gerencie seus links encurtados</p>
            </div>
            <div class="flex gap-4 items-center">
                <select id="period-filter"
                    class="rounded-lg border-black shadow-sm bg-white/10 border border-white/20 text-white px-7 py-2 focus:ring-2 focus:ring-white/50 focus:outline-none">
                    <option value="1" class="text-black" {{ $period == '1' ? 'selected' : '' }}>Hoje</option>
                    <option value="7" class="text-black" {{ $period == '7' ? 'selected' : '' }}>7 dias</option>
                    <option value="30" class="text-black" {{ $period == '30' ? 'selected' : '' }}>30 dias</option>
                </select>


                <button id="refresh-btn" class="refresh-btn bg-white/10 hover:bg-white/20 border border-white/20 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <i class="fas fa-sync-alt"></i> Atualizar
                </button>
                <a href="{{ route('links.index') }}"
                    class="bg-white text-primary hover:bg-blue-50 font-medium px-4 py-2 rounded-lg shadow-md transition-colors">
                    <i class="fas fa-plus"></i> Criar Link
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
            <!-- Métricas -->
            <div id="metrics-container" class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <div class="card metric-card bg-white p-6 relative text-indigo-600">
                    <p class="text-gray-500 text-sm font-medium">Total de Links</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1" id="total-links">{{ $totalLinks }}</h3>
                </div>
                <div class="card metric-card bg-white p-6 relative text-green-600">
                    <p class="text-gray-500 text-sm font-medium">Links Ativos</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1" id="active-links">{{ $activeLinks }}</h3>
                </div>
                <div class="card metric-card bg-white p-6 relative text-red-600">
                    <p class="text-gray-500 text-sm font-medium">Links Expirados</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1" id="expired-links">{{ $expiredLinks }}</h3>
                </div>
                <div class="card metric-card bg-white p-6 relative text-yellow-600">
                    <p class="text-gray-500 text-sm font-medium">Links Inativos</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1" id="inactive-links">{{ $inactiveLinks ?? 0 }}</h3>
                </div>
                <div class="card metric-card bg-white p-6 relative text-purple-600">
                    <p class="text-gray-500 text-sm font-medium">Total de Cliques</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1" id="total-clicks">{{ $totalClicks }}</h3>
                </div>
            </div>

            <!-- Top Links -->
            <div class="card bg-white overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Top 10 Links</h3>
                    <span class="text-xs text-gray-500">Atualizado <span id="last-update">agora</span></span>
                </div>
                <div class="p-6" id="top-links-container">
                    @if($topLinks->count() > 0)
                    <div class="space-y-3">
                        @foreach($topLinks as $index => $link)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3 flex-1">
                                <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 truncate">{{ Str::limit($link->original_url, 50) }}</div>
                                    <div class="text-xs text-gray-500">{{ url('/s/' . $link->slug) }}</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="status-badge px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $link->status === 'active' ? 'bg-green-100 text-green-800' : 
                                               ($link->status === 'expired' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($link->status) }}
                                </span>
                                <div class="text-lg font-bold text-indigo-600">{{ $link->click_count }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-4xl mb-3">📊</div>
                        <p class="text-gray-500">Nenhum link criado ainda.</p>
                        <a href="{{ route('links.index') }}" class="mt-2 inline-block text-indigo-600 hover:text-indigo-800">Criar primeiro link</a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Ação Rápida -->
            <div class="card bg-white overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800">Ação Rápida</h3>
                </div>
                <div class="p-6">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-100 text-center">
                        <div class="mb-4">
                            <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full flex items-center justify-center mx-auto text-2xl">
                                🚀
                            </div>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Criar Novo Link</h4>
                        <p class="text-gray-600 mb-6">Encurte uma URL e gere um QR Code instantaneamente</p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="{{ route('links.index') }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition-all duration-200 transform hover:-translate-y-1">
                                <i class="fas fa-plus mr-2"></i> Criar Link Agora
                            </a>
                            <a href="{{ route('links.index') }}" class="bg-white border border-gray-300 hover:border-gray-400 text-gray-700 font-medium py-3 px-6 rounded-lg transition-colors">
                                <i class="fas fa-list mr-2"></i> Ver Todos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script --}}
    <script>
        (function() {
            const refreshBtn = document.getElementById('refresh-btn');
            const periodSelect = document.getElementById('period-filter');
            const lastUpdateEl = document.getElementById('last-update');

            function spinRefresh() {
                if (!refreshBtn) return;
                refreshBtn.classList.add('spin-now');
                setTimeout(() => refreshBtn.classList.remove('spin-now'), 1000);
            }

            async function fetchJSON(url) {
                const res = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            }

            async function updateMetrics() {
                const period = periodSelect ? periodSelect.value : '7';
                if (lastUpdateEl) lastUpdateEl.textContent = 'atualizando...';
                spinRefresh();

                try {
                    const [summary, top] = await Promise.all([
                        fetchJSON(`/metrics/summary?period=${encodeURIComponent(period)}`),
                        fetchJSON(`/metrics/top?period=${encodeURIComponent(period)}`)
                    ]);

                    document.getElementById('total-links').textContent = summary?.total_links ?? 0;
                    document.getElementById('active-links').textContent = summary?.active_links ?? 0;
                    document.getElementById('expired-links').textContent = summary?.expired_links ?? 0;
                    document.getElementById('inactive-links').textContent = summary?.inactive_links ?? 0;
                    document.getElementById('total-clicks').textContent = summary?.total_clicks ?? 0;

                    renderTopLinks(Array.isArray(top?.links) ? top.links : []);

                    if (lastUpdateEl) lastUpdateEl.textContent = 'agora';
                } catch (e) {
                    console.error(e);
                    if (lastUpdateEl) lastUpdateEl.textContent = 'erro ao atualizar';
                }
            }

            function renderTopLinks(links) {
                const container = document.getElementById('top-links-container');
                if (!container) return;

                if (!links.length) {
                    container.innerHTML = `
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-3">📊</div>
                            <p class="text-gray-500">Nenhum link criado ainda.</p>
                            <a href="{{ route('links.index') }}" class="mt-2 inline-block text-indigo-600 hover:text-indigo-800">
                                Criar primeiro link
                            </a>
                        </div>
                    `;
                    return;
                }

                const html = links.map((link, index) => {
                    const statusClass =
                        link.status === 'active' ?
                        'bg-green-100 text-green-800' :
                        (link.status === 'expired' ?
                            'bg-red-100 text-red-800' :
                            'bg-yellow-100 text-yellow-800');

                    const original = (link.original_url || '');
                    const originalShort = original.length > 50 ? original.substring(0, 50) + '…' : original;
                    const shortUrl = `${window.location.origin}/s/${link.slug}`;

                    return `
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3 flex-1">
                                <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                    ${index + 1}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 truncate">${originalShort}</div>
                                    <div class="text-xs text-gray-500">${shortUrl}</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="status-badge ${statusClass}">
                                    ${link.status ? (link.status.charAt(0).toUpperCase() + link.status.slice(1)) : '—'}
                                </span>
                                <div class="text-lg font-bold text-indigo-600">
                                    ${link.click_count ?? 0}
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');

                container.innerHTML = `<div class="space-y-3">${html}</div>`;
            }

            if (periodSelect) periodSelect.addEventListener('change', updateMetrics);
            if (refreshBtn) refreshBtn.addEventListener('click', updateMetrics);

            updateMetrics();
            setInterval(updateMetrics, 30000);
        })();
    </script>
</x-app-layout>   