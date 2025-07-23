@extends('layouts.public')
@section('title', "Acompanhantes em {$cidadeNome}")

@section('content')
    <div class="container mx-auto px-2 sm:px-4 py-12">
        {{-- LINHA ALTERADA --}}
        <h1 class="text-3xl font-bold text-center text-gray-900 dark:text-white my-2">Perfis em <span class="text-blue-500">{{ $cidadeNome }}</span></h1>
        
        {{-- LINHA ALTERADA --}}
        <div class="text-center mb-8"><a href="{{ route('cidades.index') }}" class="text-blue-600 dark:text-blue-500 hover:underline">&larr; Trocar de cidade</a></div>

        {{-- SISTEMA DE FILTROS --}}
        <div x-data="{ open: false }" class="mb-12 max-w-4xl mx-auto">
            <div class="flex justify-center">
                <button @click="open = !open" type="button" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <span>Filtrar por Serviços</span>
                    <svg class="w-5 h-5 ml-2 transition-transform duration-300" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
            </div>
            <div x-show="open" x-cloak class="mt-4 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2">
                <form action="{{ route('vitrine.por.cidade', $cidadeNome) }}" method="GET">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach($servicos as $servico)
                        <label class="flex items-center">
                            <input type="checkbox" name="servicos[]" value="{{ $servico->id }}" @if(in_array($servico->id, $servicosSelecionados)) checked @endif class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ $servico->nome }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div class="flex justify-end mt-6">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[--color-primary] hover:opacity-90">Aplicar Filtros</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- SEÇÃO DE DESTAQUES --}}
        @if($destaques->isNotEmpty())
            <div class="mb-16">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">✨ Destaques</h2>
                <div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2 sm:gap-4">
                    @foreach ($destaques as $perfil)
                        @include('partials.acompanhante-card', ['perfil' => $perfil, 'isDestaque' => true])
                    @endforeach
                </div>
            </div>
        @endif

        {{-- SEÇÃO DE OUTROS PERFIS --}}
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Outros Perfis</h2>
        <div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2 sm:gap-4">
            @forelse ($acompanhantes as $perfil)
                @include('partials.acompanhante-card', ['perfil' => $perfil, 'isDestaque' => false])
            @empty
                <p class="col-span-full text-center text-gray-500 text-xl py-12">Nenhum perfil encontrado para os critérios selecionados.</p>
            @endforelse
        </div>
        
        <div class="mt-12">{{ $acompanhantes->links() }}</div>
    </div>
@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush