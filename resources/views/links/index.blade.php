<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Criar Link - Encurtador de URL</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-md border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-blue-600 rounded-xl flex items-center justify-center">
                        <span class="text-white text-lg font-bold">🔗</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">URL Shortener</h1>
                        <p class="text-xs text-gray-600">Encurte seus links facilmente</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-lg hover:bg-gray-100 transition duration-200">
                        📊 Dashboard
                    </a>
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <span>👋 Olá, {{ auth()->user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition duration-200">
                            🚪 Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full">
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-8 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl shadow-sm">
                    <div class="flex items-center space-x-2">
                        <span class="text-lg">✅</span>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Main Form -->
            <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-200">
                <div class="text-center mb-8">
                    <div class="mx-auto h-20 w-20 bg-blue-600 rounded-2xl flex items-center justify-center mb-6">
                        <span class="text-3xl">🚀</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-3">Encurtar URL</h2>
                    <p class="text-gray-600 text-lg">Transforme links longos em URLs curtas e poderosas</p>
                </div>

                <form method="POST" action="{{ route('links.store') }}" class="space-y-8">
                    @csrf
                    
                    <!-- URL Input -->
                    <div class="space-y-3">
                        <label for="original_url" class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            URL para encurtar
                        </label>
                        <div class="relative">
                            <input type="url" 
                                   name="original_url" 
                                   id="original_url" 
                                   required
                                   value="{{ old('original_url') }}"
                                   placeholder="https://exemplo.com/sua-url-muito-longa-que-precisa-ser-encurtada"
                                   class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300 bg-gray-50 hover:bg-white">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-6">
                                <span class="text-2xl">🌐</span>
                            </div>
                        </div>
                        @error('original_url')
                            <p class="text-red-600 text-sm font-medium flex items-center space-x-1">
                                <span>❌</span>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Expiration Input -->
                    <div class="space-y-3">
                        <label for="expires_at" class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            Data de expiração (opcional)
                        </label>
                        <div class="relative">
                            <input type="datetime-local" 
                                   name="expires_at" 
                                   id="expires_at"
                                   value="{{ old('expires_at') }}"
                                   min="{{ now()->format('Y-m-d\TH:i') }}"
                                   class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300 bg-gray-50 hover:bg-white">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-6">
                                <span class="text-2xl">⏰</span>
                            </div>
                        </div>
                        <p class="text-gray-500 text-sm flex items-center space-x-1">
                            <span>💡</span>
                            <span>Deixe em branco para um link permanente</span>
                        </p>
                        @error('expires_at')
                            <p class="text-red-600 text-sm font-medium flex items-center space-x-1">
                                <span>❌</span>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-xl text-lg shadow-lg hover:shadow-xl transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-300">
                            <span class="flex items-center justify-center space-x-3">
                                <span class="text-2xl">✨</span>
                                <span>Encurtar Agora</span>
                                <span class="text-2xl">🚀</span>
                            </span>
                        </button>
                    </div>
                </form>

                <!-- Quick Actions -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center justify-center space-x-2 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 px-4 rounded-xl transition duration-200 font-medium">
                            <span>📊</span>
                            <span>Ver Dashboard</span>
                        </a>
                        @if($links->count() > 0)
                            <button onclick="showLinks()" 
                                    class="flex items-center justify-center space-x-2 bg-blue-100 hover:bg-blue-200 text-blue-700 py-3 px-4 rounded-xl transition duration-200 font-medium">
                                <span>📋</span>
                                <span>Meus Links ({{ $links->count() }})</span>
                            </button>
                        @else
                            <div class="flex items-center justify-center space-x-2 bg-gray-50 text-gray-400 py-3 px-4 rounded-xl font-medium">
                                <span>📋</span>
                                <span>Nenhum link ainda</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Links List (Hidden by default) -->
            @if($links->count() > 0)
                <div id="linksList" class="hidden mt-8 bg-white shadow-xl rounded-2xl p-8 border border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Meus Links</h3>
                        <button onclick="hideLinks()" class="text-gray-400 hover:text-gray-600 text-2xl">✕</button>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach ($links as $link)
                            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                                                {{ $link->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $link->status === 'expired' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $link->status === 'inactive' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                                {{ $link->status === 'active' ? '✅ Ativo' : '' }}
                                                {{ $link->status === 'expired' ? '❌ Expirado' : '' }}
                                                {{ $link->status === 'inactive' ? '⏸️ Inativo' : '' }}
                                            </span>
                                            <span class="text-sm text-gray-500">👆 {{ $link->click_count }} cliques</span>
                                        </div>
                                        <p class="text-gray-900 font-medium truncate mb-1">{{ $link->original_url }}</p>
                                        <div class="flex items-center space-x-3">
                                            <p class="text-blue-600 font-mono text-sm">{{ url('/s/' . $link->slug) }}</p>
                                            <button onclick="copyToClipboard('{{ url('/s/' . $link->slug) }}')" 
                                                    class="text-gray-400 hover:text-blue-600 transition-colors text-lg"
                                                    title="Copiar link">
                                                📋
                                            </button>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('links.show', $link) }}" 
                                           class="text-blue-600 hover:text-blue-800 transition-colors text-xl"
                                           title="Ver detalhes">
                                            👁️
                                        </a>
                                        <a href="{{ route('links.qrcode', $link) }}" 
                                           class="text-purple-600 hover:text-purple-800 transition-colors text-xl"
                                           title="QR Code" target="_blank">
                                            📱
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($links->hasPages())
                        <div class="mt-6">
                            {{ $links->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </main>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success message
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 flex items-center space-x-2';
                toast.innerHTML = '<span>✅</span><span>Link copiado!</span>';
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            });
        }

        function showLinks() {
            document.getElementById('linksList').classList.remove('hidden');
        }

        function hideLinks() {
            document.getElementById('linksList').classList.add('hidden');
        }

        // Auto-focus URL input
        document.getElementById('original_url').focus();

        // Form validation feedback
        const urlInput = document.getElementById('original_url');
        urlInput.addEventListener('input', function() {
            if (this.validity.valid) {
                this.classList.remove('border-red-300');
                this.classList.add('border-green-300');
            } else {
                this.classList.remove('border-green-300');
                this.classList.add('border-red-300');
            }
        });
    </script>
</body>
</html>