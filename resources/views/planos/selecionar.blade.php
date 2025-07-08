<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Escolha o seu Plano
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <p class="text-lg text-gray-600 dark:text-gray-400">Selecione o plano que melhor se adapta às suas necessidades para começar.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($planos as $plano)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 flex flex-col">
                        <h2 class="text-2xl font-bold text-center text-[--color-primary]">{{ $plano->nome }}</h2>
                        <p class="text-4xl font-extrabold text-center my-4 dark:text-white">R$ {{ number_format($plano->preco, 2, ',', '.') }}</p>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400 flex-grow">
                            <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>{{ $plano->limite_fotos }} Fotos na Galeria</li>
                            <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>{{ $plano->limite_videos }} Vídeos na Galeria</li>
                            <li class="flex items-center @if(!$plano->permite_destaque) text-gray-400 line-through @endif"><svg class="w-5 h-5 {{ $plano->permite_destaque ? 'text-green-500' : 'text-red-500' }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $plano->permite_destaque ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}"></path></svg>Perfil em Destaque</li>
                        </ul>
                        <form action="{{ route('planos.assinar', $plano) }}" method="POST" class="mt-6">
                            @csrf
                            <button type="submit" class="w-full bg-[--color-primary] text-white font-bold py-3 px-6 rounded-lg hover:opacity-90 transition-opacity">Assinar Plano</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
