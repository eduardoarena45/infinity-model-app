@php
use Illuminate\Support\Facades\Storage;
@endphp

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transform hover:-translate-y-1 transition-all duration-300
    @if($isDestaque) border-2 border-yellow-400 @endif">
    <a href="{{ route('vitrine.show', $perfil->id) }}" class="block">
        <div class="relative">
            {{-- MELHORIA: Altura da imagem responsiva, menor no celular --}}
            <img src="{{ $perfil->foto_principal_url }}" alt="Foto de {{ $perfil->nome_artistico }}" class="w-full h-48 sm:h-64 object-cover">
            
            @if($perfil->is_verified)
            <div class="absolute top-2 right-2" title="Perfil Verificado">
                <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-500 text-white rounded-full shadow">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                </span>
            </div>
            @endif
        </div>
        
        {{-- MELHORIA: Padding menor para celular --}}
        <div class="p-2 sm:p-3">
            {{-- MELHORIA: Fontes menores para celular e classe 'truncate' para evitar quebra de layout --}}
            <h3 class="text-sm sm:text-base font-semibold text-[--color-primary] dark:text-white truncate">{{ $perfil->nome_artistico }}</h3>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">{{ $perfil->cidade->nome ?? 'Cidade' }}</p>
            
            <div class="flex items-center mt-1">
                @for ($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= $perfil->avaliacoes->avg('nota') ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                @endfor
                <span class="ml-1 text-xs text-gray-400">({{ $perfil->avaliacoes->count() }})</span>
            </div>
            
            {{-- MELHORIA: Fonte e margem ajustadas, com "/ hora" menor para melhor visual --}}
            <p class="text-base sm:text-lg font-bold text-green-600 dark:text-green-400 mt-2">
                R$ {{ number_format($perfil->valor_hora, 0, ',', '.') }} 
                <span class="text-xs font-normal text-gray-500">/ hora</span>
            </p>
        </div>
    </a>
</div>