@php
use Illuminate\Support\Facades\Storage;
@endphp

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transform hover:-translate-y-1 transition-all duration-300
    @if($isDestaque) border-2 border-yellow-400 @endif">
    
    {{-- Imagem clicável --}}
    <a href="{{ route('vitrine.show', $perfil->id) }}" class="block">
        <div class="relative">
            <img src="{{ $perfil->foto_principal_url }}" alt="Foto de {{ $perfil->nome_artistico }}" class="w-full h-48 sm:h-64 object-cover">
            
            @if($perfil->is_verified)
            <div class="absolute top-2 right-2" title="Perfil Verificado">
                <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-500 text-white rounded-full shadow">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                </span>
            </div>
            @endif
        </div>
    </a>
    
    <div class="p-2 sm:p-3">
        {{-- Nome, cidade e avaliações clicáveis --}}
        <a href="{{ route('vitrine.show', $perfil->id) }}" class="block">
            <h3 class="text-sm sm:text-base font-semibold text-[--color-primary] dark:text-white truncate">{{ $perfil->nome_artistico }}</h3>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">{{ $perfil->cidade->nome ?? 'Cidade' }}</p>
            
            <div class="flex items-center mt-1">
                @for ($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= $perfil->avaliacoes->avg('nota') ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                @endfor
                <span class="ml-1 text-xs text-gray-400">({{ $perfil->avaliacoes->count() }})</span>
            </div>
        </a>

        {{-- ======================================================= --}}
        {{-- === NOVO BLOCO DE PREÇOS EXPANSÍVEL ADICIONADO AQUI === --}}
        {{-- ======================================================= --}}
        <div x-data="{ open: false }" class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
                {{-- Preço Principal --}}
                <a href="{{ route('vitrine.show', $perfil->id) }}" class="block">
                    <p class="text-base sm:text-lg font-bold text-green-600 dark:text-green-400">
                        R$ {{ number_format($perfil->valor_hora, 2, ',', '.') }} 
                        <span class="text-xs font-normal text-gray-500">/ hora</span>
                    </p>
                </a>

                {{-- Botão de Seta (só aparece se houver outros preços) --}}
                @if($perfil->valor_15_min || $perfil->valor_30_min || $perfil->valor_pernoite)
                    <button @click="open = !open" class="p-2 -mr-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                @endif
            </div>

            {{-- Tabela de Preços Oculta --}}
            <div x-show="open" x-cloak x-transition class="mt-2 pt-2 border-t border-gray-100 dark:border-gray-700/50 space-y-1 text-xs sm:text-sm">
                @if($perfil->valor_15_min)
                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                        <span>15 Minutos</span>
                        <span class="font-semibold">R$ {{ number_format($perfil->valor_15_min, 2, ',', '.') }}</span>
                    </div>
                @endif
                @if($perfil->valor_30_min)
                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                        <span>30 Minutos</span>
                        <span class="font-semibold">R$ {{ number_format($perfil->valor_30_min, 2, ',', '.') }}</span>
                    </div>
                @endif
                @if($perfil->valor_pernoite)
                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                        <span>Pernoite</span>
                        <span class="font-semibold">R$ {{ number_format($perfil->valor_pernoite, 2, ',', '.') }}</span>
                    </div>
                @endif
            </div>
        </div>
        {{-- ======================================================= --}}
        {{-- =================== FIM DO BLOCO DE PREÇOS ================== --}}
        {{-- ======================================================= --}}
    </div>
</div>
