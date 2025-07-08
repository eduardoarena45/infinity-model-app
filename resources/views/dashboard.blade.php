<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Painel de Controle</h2></x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium">Olá, {{ Auth::user()->name }}!</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Use os links abaixo para gerir a sua conta.</p>
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <a href="{{ route('profile.edit') }}" class="block p-6 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                            <h4 class="font-bold text-lg">Editar Perfil</h4>
                            <p class="text-sm text-gray-500">Altere as suas informações públicas.</p>
                        </a>
                        <a href="{{ route('galeria.gerir') }}" class="block p-6 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                            <h4 class="font-bold text-lg">Gerir Galeria</h4>
                            <p class="text-sm text-gray-500">Adicione ou remova fotos e vídeos.</p>
                        </a>
                        <a href="{{ route('planos.selecionar') }}" class="block p-6 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                            <h4 class="font-bold text-lg">Meu Plano</h4>
                            <p class="text-sm text-gray-500">Veja ou altere o seu plano de assinatura.</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>