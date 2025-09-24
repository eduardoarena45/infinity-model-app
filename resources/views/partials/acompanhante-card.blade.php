@php
use Illuminate\Support\Facades\Storage;
@endphp

{{-- ======================================================= --}}
{{-- =================== INÍCIO DA REESTRUTURAÇÃO ================== --}}
{{-- ======================================================= --}}

{{-- O x-data inicializa o estado do nosso carrossel para cada card individualmente --}}
<div x-data="{
        // O array de imagens começa apenas com a foto principal.
        images: ['{{ $perfil->foto_principal_url }}'],
        // O índice da imagem atual começa em 0 (a primeira).
        currentIndex: 0,
        // Controla se estamos a carregar as imagens da galeria.
        isLoading: false,
        // Garante que só carregamos a galeria uma vez.
        galleryLoaded: false,

        // Função para ir para a próxima imagem.
        nextImage() {
            // Se estamos na primeira imagem e a galeria ainda não foi carregada...
            if (this.currentIndex === 0 && !this.galleryLoaded) {
                this.loadGallery(); // ...chama a função para carregar as fotos.
            } else if (this.currentIndex < this.images.length - 1) {
                this.currentIndex++;
            }
        },

        // Função para ir para a imagem anterior.
        prevImage() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
            }
        },

        // Função que vai buscar as fotos da galeria à nossa nova API.
        loadGallery() {
            if (this.galleryLoaded || this.isLoading) return; // Previne múltiplas chamadas

            this.isLoading = true;
            fetch(`/api/acompanhante/{{ $perfil->id }}/galeria`)
                .then(response => response.json())
                .then(galleryUrls => {
                    // Adiciona a foto principal ao início da lista de fotos da galeria
                    const allImages = ['{{ $perfil->foto_principal_url }}', ...galleryUrls];
                    // Remove duplicados (caso a foto principal também esteja na galeria)
                    this.images = [...new Set(allImages)];

                    this.galleryLoaded = true;
                    this.isLoading = false;
                    // Só avança para a próxima imagem se a galeria tiver mais de uma foto.
                    if (this.images.length > 1) {
                        this.currentIndex = 1; // Avança para a primeira foto da galeria
                    }
                })
                .catch(() => {
                    this.isLoading = false;
                    this.galleryLoaded = true; // Marca como carregado para não tentar de novo
                });
        }
    }"
    class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transform hover:-translate-y-1 transition-all duration-300 group
           @if($isDestaque) border-2 border-yellow-400 @endif">

    {{-- O contentor da imagem agora é relativo para posicionar as setas --}}
    <div class="relative">
        <a href="{{ route('vitrine.show', $perfil) }}">
            <img :src="images[currentIndex]" alt="Foto de {{ $perfil->nome_artistico }}" class="w-full h-48 sm:h-64 object-cover transition-opacity duration-300">
        </a>

        <!-- Ícone de Carregamento (Loading) -->
        <div x-show="isLoading" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
            <svg class="animate-spin h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </div>

        {{-- Seta Esquerda --}}
        <button @click.prevent="prevImage()"
                x-show="currentIndex > 0"
                class="absolute left-2 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity focus:outline-none"
                x-cloak>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>

        {{-- Seta Direita --}}
        <button @click.prevent="nextImage()"
                x-show="images.length > 1 || !galleryLoaded"
                class="absolute right-2 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity focus:outline-none"
                x-cloak>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>

        {{-- Pontos de Navegação (só aparecem se houver mais de uma foto) --}}
        <div x-show="images.length > 1" class="absolute bottom-2 left-1/2 -translate-x-1/2 flex space-x-2" x-cloak>
            <template x-for="(image, index) in images" :key="index">
                <button @click="currentIndex = index"
                        class="w-2 h-2 rounded-full transition"
                        :class="{'bg-white': currentIndex === index, 'bg-white/50 hover:bg-white/75': currentIndex !== index}"></button>
            </template>
        </div>

        @if($perfil->is_verified)
            <div class="absolute top-2 right-2" title="Perfil Verificado">
                 <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-500 text-white rounded-full shadow">
                     <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                 </span>
            </div>
        @endif
    </div>

    {{-- O resto do seu card, que já está perfeito, continua aqui sem alterações --}}
    <div class="p-2 sm:p-3">
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

        <div x-data="{ open: false }" class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
                <a href="{{ route('vitrine.show', $perfil->id) }}" class="block">
                    <p class="text-base sm:text-lg font-bold text-green-600 dark:text-green-400">
                        R$ {{ number_format($perfil->valor_hora, 2, ',', '.') }}
                        <span class="text-xs font-normal text-gray-500">/ hora</span>
                    </p>
                </a>
                @if($perfil->valor_15_min || $perfil->valor_30_min || $perfil->valor_pernoite)
                    <button @click="open = !open" class="p-2 -mr-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                @endif
            </div>
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
    </div>
</div>

