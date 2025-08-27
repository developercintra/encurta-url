<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Perfil') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium">Informações da Conta</h3>
                <p><b>Nome:</b> {{ Auth::user()->name }}</p>
                <p><b>Email:</b> {{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
s