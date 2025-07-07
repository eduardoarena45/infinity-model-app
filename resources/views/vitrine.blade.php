@extends('layouts.public')
@section('title', "Acompanhantes em {$cidadeNome}")

@section('content')
    <div class="container mx-auto p-4 py-12">
        <h1 class="text-3xl font-bold text-center text-gray-900 dark:text-white my-2">Perfis em <span class="text-[--color-accent]">{{ $cidadeNome }}</span></h1>
        <div class="text-center mb-8"><a href="{{ route('cidades.index') }}" class="text-[--color-primary] dark:text-[--color-accent] hover:underline">&larr; Trocar de cidade</a></div>

        <div class="mb-12 max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <form action="{{ route('vitrine.por.cidade', $cidadeNome) }}" method="GET">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Filtrar por Serviços</h3>
                <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($servicos as $servico)
                    <label class="flex items-center">
                        <input type="checkbox" name="servicos[]" value="{{ $servico->id }}" @if(in_array($servico->id, $servicosSelecionados)) checked @endif class="h-4 w-4 text-[--color-primary] border-gray-300 rounded focus:ring-[--color-accent]">
                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ $servico->nome }}</span>
                    </label>
                    @endforeach
                </div>
                <div class="flex justify-end mt-6"><button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[--color-primary] hover:opacity-90">Aplicar Filtros</button></div>
            </form>
        </div>

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

        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Outros Perfis</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($acompanhantes as $perfil)
                @include('partials.acompanhante-card', ['perfil' => $perfil, 'isDestaque' => false])
            @empty
                <p class="col-span-full text-center text-gray-500 text-xl py-12">Nenhum perfil encontrado para os critérios selecionados.</p>
            @endforelse
        </div>
        <div class="mt-12">{{ $acompanhantes->links() }}</div>
    </div>
@endsection