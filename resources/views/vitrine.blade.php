@extends('layouts.public')
@section('title', "Acompanhantes em {$cidadeNome}")

@section('content')
    @php use Illuminate\Support\Facades\Storage; @endphp
    <div class="container mx-auto p-4 py-12">
        <h1 class="text-3xl font-bold text-center text-gray-900 dark:text-white my-2">Perfis em Destaque em <span class="text-pink-600">{{ $cidadeNome }}</span></h1>
        <div class="text-center mb-8">
            <a href="{{ route('cidades.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Trocar de cidade</a>
        </div>

        {{-- NOVO FORMULÁRIO DE FILTRO DE SERVIÇOS --}}
        <div class="mb-12 max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <form action="{{ route('vitrine.por.cidade', $cidadeNome) }}" method="GET">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Filtrar por Serviços</h3>
                <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($servicos as $servico)
                    <label for="servico_{{ $servico->id }}" class="flex items-center">
                        <input id="servico_{{ $servico->id }}" name="servicos[]" type="checkbox" value="{{ $servico->id }}"
                               @if(in_array($servico->id, $servicosSelecionados)) checked @endif
                               class="h-4 w-4 text-pink-600 border-gray-300 rounded focus:ring-pink-500 dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ $servico->nome }}</span>
                    </label>
                    @endforeach
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                        Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>


        {{-- Grelha de Perfis --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($acompanhantes as $perfil)
                @if($perfil->nome_artistico)
                <a href="{{ route('vitrine.show', $perfil->id) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <img src="{{ Storage::url($perfil->imagem_principal_url) }}" alt="Foto de {{ $perfil->nome_artistico }}" class="w-full h-80 object-cover">
                    <div class="p-4">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $perfil->nome_artistico }}</h2>
                        <p class="text-gray-600 dark:text-gray-400">{{ $perfil->cidade }}, {{ $perfil->estado }}</p>
                        <div class="mt-4 text-lg font-bold text-pink-600">R$ {{ number_format($perfil->valor_hora, 2, ',', '.') }} / hora</div>
                    </div>
                </a>
                @endif
            @empty
                <p class="col-span-full text-center text-gray-500 text-xl py-12">Nenhum perfil encontrado para os critérios selecionados.</p>
            @endforelse
        </div>
    </div>
@endsection