@extends('layouts.onboarding')

@section('title', 'Escolha o seu Plano')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Escolha o seu Plano</h1>
            <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">Selecione o plano que melhor se adapta às suas necessidades para começar.</p>

            {{-- ======================================================= --}}
            {{-- =================== INÍCIO DA ALTERAÇÃO ================== --}}
            {{-- ======================================================= --}}

            {{-- Botão "Voltar" com estilo melhorado, restaurado da versão anterior --}}
            <div class="mt-6">
                <a href="{{ route('dashboard') }}" class="inline-block bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-semibold px-5 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors shadow-sm">
                    &larr; Voltar para o Painel
                </a>
            </div>

            {{-- ======================================================= --}}
            {{-- ==================== FIM DA ALTERAÇÃO ===================== --}}
            {{-- ======================================================= --}}

        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            @foreach($planos as $plano)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 flex flex-col
                    @if($plano->id === $assinaturaAtivaId)
                        border-2 border-green-500
                    @elseif($plano->destaque)
                        border-2 border-yellow-500
                    @else
                        border-2 border-gray-200 dark:border-gray-700
                    @endif">

                    <h2 class="text-2xl font-bold text-center @if($plano->preco == 0) text-gray-500 dark:text-gray-400 @else text-[--color-primary] dark:text-cyan-400 @endif">{{ $plano->nome }}</h2>
                    <p class="text-4xl font-extrabold text-center my-4 dark:text-white">R$ {{ number_format($plano->preco, 2, ',', '.') }}</p>

                    <ul class="space-y-2 text-gray-600 dark:text-gray-400 flex-grow">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Perfil Verificado
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            WhatsApp Visível
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            @if($plano->limite_descricao)
                                Descrição com {{ $plano->limite_descricao }} caracteres
                            @else
                                Descrição Ilimitada
                            @endif
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ $plano->limite_fotos }} Fotos na Galeria
                        </li>
                        @if ($plano->permite_videos)
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ $plano->limite_videos }} Vídeos na Galeria
                            </li>
                        @else
                            <li class="flex items-center text-gray-400 line-through">
                                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Vídeos na Galeria
                            </li>
                        @endif
                        @if ($plano->destaque)
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Perfil em Destaque
                            </li>
                        @else
                            <li class="flex items-center text-gray-400 line-through">
                                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Perfil em Destaque
                            </li>
                        @endif
                    </ul>

                    <div class="mt-6">
                        @if ($plano->id === $assinaturaAtivaId)
                            <span class="w-full block text-center bg-green-500 text-white font-bold py-3 px-6 rounded-lg">Seu Plano Atual</span>
                        @else
                            <form action="{{ route('planos.assinar', $plano) }}" method="POST">
                                @csrf
                                @if($plano->preco == 0)
                                    <button type="submit" class="w-full bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition-colors">Selecionar Grátis</button>
                                @else
                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">Assinar Plano</button>
                                @endif
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

