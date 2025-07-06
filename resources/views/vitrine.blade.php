@extends('layouts.public')
@section('title', "Acompanhantes em {$cidadeNome}")

@section('content')
    <div class="container mx-auto p-4 py-12">
        <h1 class="text-3xl font-bold text-center text-gray-900 dark:text-white my-2">Perfis em <span class="text-pink-600">{{ $cidadeNome }}</span></h1>
        <div class="text-center mb-8"><a href="{{ route('cidades.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Trocar de cidade</a></div>

        {{-- Formulário de Filtro --}}
        <div class="mb-12 max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            {{-- ... (código do formulário de filtro que já tem) ... --}}
        </div>

        <!-- SECÇÃO DE DESTAQUES -->
        @if($destaques->isNotEmpty())
            <div class="mb-16">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">✨ Destaques</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($destaques as $perfil)
                        @include('partials.acompanhante-card', ['perfil' => $perfil, 'isDestaque' => true])
                    @endforeach
                </div>
            </div>
        @endif

        <!-- SECÇÃO DE OUTROS PERFIS -->
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Outros Perfis</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($acompanhantes as $perfil)
                @include('partials.acompanhante-card', ['perfil' => $perfil, 'isDestaque' => false])
            @empty
                <p class="col-span-full text-center text-gray-500 text-xl py-12">Nenhum perfil encontrado para os critérios selecionados.</p>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $acompanhantes->links() }}
        </div>
    </div>
@endsection