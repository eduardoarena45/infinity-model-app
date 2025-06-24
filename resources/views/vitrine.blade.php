@extends('layouts.public')

{{-- O título é dinâmico, baseado no que o controller envia --}}
@section('title', $titulo ?? "Acompanhantes em {$cidadeNome}")

@section('content')
    @php use Illuminate\Support\Facades\Storage; @endphp
    <div class="container mx-auto p-4 py-12">
        <h1 class="text-3xl font-bold text-center text-gray-900 dark:text-white my-2">
            {{ $titulo ?? "Perfis em Destaque em" }} <span class="text-pink-600">{{ $cidadeNome ?? '' }}</span>
        </h1>
        <div class="text-center mb-8">
            <a href="{{ route('cidades.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Voltar para a busca</a>
        </div>

        {{-- Formulário de Filtro --}}
        @isset($servicos)
        <div class="mb-12 max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <form action="{{ route('vitrine.por.cidade', $cidadeNome) }}" method="GET">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Filtrar por Serviços</h3>
                <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($servicos as $servico)
                    <label for="servico_{{ $servico->id }}" class="flex items-center">
                        <input id="servico_{{ $servico->id }}" name="servicos[]" type="checkbox" value="{{ $servico->id }}" @if(in_array($servico->id, $servicosSelecionados ?? [])) checked @endif class="h-4 w-4 text-pink-600 border-gray-300 rounded focus:ring-pink-500 dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ $servico->nome }}</span>
                    </label>
                    @endforeach
                </div>
                <div class="flex justify-end mt-6"><button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-pink-600 hover:bg-pink-700">Aplicar Filtros</button></div>
            </form>
        </div>
        @endisset

        {{-- Grelha de Perfis --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($acompanhantes as $perfil)
                @if($perfil->nome_artistico)
                <a href="{{ route('vitrine.show', $perfil->id) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <img src="{{ Storage::url($perfil->imagem_principal_url) }}" alt="Foto de {{ $perfil->nome_artistico }}" class="w-full h-80 object-cover">
                    <div class="p-4">
                        {{-- BLOCO MODIFICADO PARA INCLUIR O SELO --}}
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white truncate">
                                {{ $perfil->nome_artistico }}
                            </h2>
                            {{-- Condição para mostrar o selo de verificado --}}
                            @if($perfil->is_verified)
                                <span title="Perfil Verificado">
                                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a.75.75 0 00-1.06-1.06L9 10.94l-1.72-1.72a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.06 0l3.75-3.75z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            @endif
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $perfil->cidade }}, {{ $perfil->estado }}</p>
                        <div class="mt-4 text-lg font-bold text-pink-600">R$ {{ number_format($perfil->valor_hora, 2, ',', '.') }} / hora</div>
                    </div>
                </a>
                @endif
            @empty
                <p class="col-span-full text-center text-gray-500 text-xl py-12">Nenhum perfil encontrado para os critérios selecionados.</p>
            @endforelse
        </div>

        {{-- Links de Paginação --}}
        <div class="mt-12">
            {{ $acompanhantes->links() }}
        </div>
    </div>
@endsection
