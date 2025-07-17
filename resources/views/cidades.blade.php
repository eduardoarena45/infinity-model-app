@extends('layouts.public')
@section('title', 'Bem-vindo(a) - Encontre a melhor companhia')

@section('content')

<!-- INÍCIO DO BANNER -->
<div class="relative bg-black">
    <div aria-hidden="true" class="absolute inset-0 overflow-hidden">
        <img src="{{ asset('images/meu-banner.jpg') }}" alt="Banner principal do Infinity Model" class="w-full h-full object-center object-cover">
    </div>
    <div aria-hidden="true" class="absolute inset-0 bg-black opacity-75"></div>
    <div class="relative max-w-4xl mx-auto py-32 px-6 flex flex-col items-center text-center sm:py-48 lg:px-0">
        <h1 class="text-4xl font-extrabold tracking-tight text-white lg:text-6xl" style="text-shadow: 2px 2px 8px rgba(0,0,0,0.8);">
            Bem-vindo ao maior site de acompanhantes do Brasil
        </h1>
        <p class="mt-4 text-xl text-gray-200" style="text-shadow: 1px 1px 4px rgba(0,0,0,0.8);">
            Explore perfis verificados e encontre a companhia ideal para momentos únicos.
        </p>
        <button id="open-cities-modal-btn" class="mt-8 inline-block bg-white border border-transparent rounded-md py-3 px-8 text-base font-medium text-black hover:bg-gray-200 transition-colors">
            Ver Cidades
        </button>
    </div>
</div>
<!-- FIM DO BANNER -->

<!-- INÍCIO DO POP-UP (MODAL) DE CIDADES -->
<div id="cities-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 sm:p-8 max-w-lg w-full m-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white">Selecione uma Cidade</h2>
            <button id="close-cities-modal-btn" class="text-gray-500 hover:text-gray-800 dark:hover:text-white text-3xl leading-none">&times;</button>
        </div>
        <div class="max-h-[60vh] overflow-y-auto pr-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse ($cidades as $cidade)
                    {{-- CORREÇÃO AQUI: Usa $cidade->nome para o link e para o texto --}}
                    <a href="{{ route('vitrine.por.cidade', ['cidade' => $cidade->nome]) }}" class="city-item block text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                        <h3 class="font-bold text-lg text-[--color-primary] dark:text-white">{{ $cidade->nome }}</h3>
                    </a>
                @empty
                    <p class="col-span-full text-center text-gray-500">Nenhuma cidade com perfis disponíveis no momento.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
<!-- FIM DO POP-UP (MODAL) DE CIDADES -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const openBtn = document.getElementById('open-cities-modal-btn');
        const closeBtn = document.getElementById('close-cities-modal-btn');
        const modal = document.getElementById('cities-modal');

        if (openBtn && modal) {
            openBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
            });
        }
        if (closeBtn && modal) {
            closeBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        }
        if (modal) {
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        }
    });
</script>
@endsection
