@extends('layouts.public')
@section('title', 'Bem-vindo(a) - Encontre a melhor companhia')

@section('content')
<div class="relative bg-[--color-neutral]">
    <div aria-hidden="true" class="absolute inset-0 overflow-hidden"><img src="{{ asset('images/meu-banner.jpg') }}" alt="Banner Infinity Model" class="w-full h-full object-center object-cover"></div>
    <div aria-hidden="true" class="absolute inset-0 bg-[--color-neutral] opacity-60"></div>
    <div class="relative max-w-3xl mx-auto py-32 px-6 flex flex-col items-center text-center sm:py-48 lg:px-0">
        <h1 class="text-4xl font-extrabold tracking-tight text-white lg:text-6xl">A sua melhor experiência</h1>
        <p class="mt-4 text-xl text-white">Explore perfis verificados nas principais cidades e encontre a companhia perfeita para qualquer ocasião.</p>
        <a href="#vitrines" class="mt-8 inline-block bg-white border border-transparent rounded-md py-3 px-8 text-base font-medium text-[--color-neutral] hover:bg-gray-100">Ver Vitrines</a>
    </div>
</div>
<div id="vitrines" class="py-16 bg-white dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-extrabold text-center text-gray-900 dark:text-white">Selecione uma Vitrine</h2>
        <div class="mt-8 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse ($cidades as $cidade)
                <a href="{{ route('vitrine.por.cidade', ['cidade' => $cidade->cidade]) }}" class="city-item block text-center p-6 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-md hover:bg-gray-100 dark:hover:bg-[--color-neutral] transition-colors">
                    <h3 class="font-bold text-lg text-[--color-primary] dark:text-white">{{ $cidade->cidade }}</h3>
                </a>
            @empty
                <p class="col-span-full text-center text-gray-500">Nenhuma cidade com perfis disponíveis no momento.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
