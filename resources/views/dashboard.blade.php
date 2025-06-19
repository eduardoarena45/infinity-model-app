{{-- Este é o layout padrão que inclui o menu de navegação --}}
<x-app-layout>
    {{-- Esta é a barra cinza no topo da página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Painel de Controle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Mensagem de boas-vindas --}}
                    <p class="mb-4">Olá, {{ Auth::user()->name }}! Bem-vinda ao seu painel.</p>

                    {{-- O BOTÃO QUE FALTA! Ele leva para a rota 'profile.edit' que criamos. --}}
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Gerenciar Meu Perfil Público
                    </a>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
