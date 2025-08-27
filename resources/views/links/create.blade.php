<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Criar Link') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('links.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="original_url" class="block text-sm font-medium text-gray-700">URL Original</label>
                        <input type="url" name="original_url" id="original_url" required
                            class="w-full p-2 mt-1 border rounded">
                    </div>

                    <button type="submit" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                        Salvar
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
