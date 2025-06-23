@extends('layouts.public')

@section('title', "Acompanhantes em {$cidadeNome}")

@section('content')
    @php use Illuminate\Support\Facades\Storage; @endphp
    <div class="container mx-auto p-4 py-12">
        <h1 class="text-3xl font-bold text-center text-gray-900 dark:text-white my-8">Perfis em Destaque em <span class="text-pink-600">{{ $cidadeNome }}</span></h1>
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
                <p class="col-span-full text-center text-gray-500">Nenhum perfil encontrado para esta cidade.</p>
            @endforelse
        </div>
    </div>
@endsection