<section class="space-y-8">
    {{-- ======================================================= --}}
    {{-- =================== SEÇÃO DE FOTOS ==================== --}}
    {{-- ======================================================= --}}
    <div>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Minhas Fotos</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Adicione ou remova fotos da sua galeria. As fotos novas precisarão de aprovação.
            </p>
        </header>

        @if(session('error_message') && session('type') === 'photo')
            <div class="p-4 my-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-900 dark:text-red-400" role="alert">
                {{ session('error_message') }}
            </div>
        @endif

        @if ($photo_count < $photo_limit)
            <form method="post" action="{{ route('galeria.upload') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                @csrf
                <div>
                    <x-input-label for="fotos">
                        <span>Adicionar Novas Fotos ({{ $photo_count }} de {{ $photo_limit }})</span><span class="text-red-500 ml-1">*</span>
                    </x-input-label>
                    <input id="fotos" name="fotos[]" type="file" class="mt-1 block w-full text-gray-900 dark:text-gray-100" multiple required accept="image/*" />
                    <x-input-error class="mt-2" :messages="$errors->get('fotos')" />
                    <x-input-error class="mt-2" :messages="$errors->get('fotos.*')" />
                </div>
                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Enviar Fotos') }}</x-primary-button>
                </div>
            </form>
        @else
            <div class="p-4 mt-6 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-900 dark:text-yellow-300" role="alert">
                <span class="font-medium">Limite de fotos atingido!</span> Você já tem {{ $photo_count }} de {{ $photo_limit }} fotos permitidas.
            </div>
        @endif

        <hr class="my-8 border-gray-200 dark:border-gray-700">
        <div>
            <h3 class="text-md font-medium text-gray-900 dark:text-gray-100">Fotos Atuais</h3>
            @if($media->where('type', 'image')->isNotEmpty())
                <div class="mt-4 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    {{-- ======================================================= --}}
                    {{-- ============= INÍCIO DO CÓDIGO RESTAURADO ============== --}}
                    {{-- ======================================================= --}}
                    @foreach($media->where('type', 'image') as $foto)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $foto->path) }}" class="rounded-lg object-cover w-full h-40" alt="Foto da galeria">
                            <div class="absolute inset-0 bg-black bg-opacity-20 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-white text-xs font-bold capitalize bg-black bg-opacity-50 px-2 py-1 rounded mb-1">{{ $foto->status }}</span>
                                <form method="POST" action="{{ route('galeria.destroy', $foto->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white rounded-full p-1 leading-none" onclick="return confirm('Tem certeza que deseja apagar esta foto?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                    {{-- ======================================================= --}}
                    {{-- =============== FIM DO CÓDIGO RESTAURADO =============== --}}
                    {{-- ======================================================= --}}
                </div>
            @else
                <div class="p-4 mt-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-900 dark:text-yellow-300" role="alert">
                    <span class="font-medium">A sua galeria está vazia!</span> É obrigatório adicionar pelo menos uma foto para que o seu perfil seja visível na vitrine.
                </div>
            @endif
        </div>
    </div>

    <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Meus Vídeos</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Adicione ou remova vídeos da sua galeria.
            </p>
        </header>

        @if(session('error_message') && session('type') === 'video')
            <div class="p-4 my-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-900 dark:text-red-400" role="alert">
                {{ session('error_message') }}
            </div>
        @endif

        @if ($video_limit > 0 && $video_count < $video_limit)
            <form method="post" action="{{ route('galeria.upload.video') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                @csrf
                <div>
                    <x-input-label for="videos" :value="'Adicionar Novos Vídeos (' . $video_count . ' de ' . $video_limit . ')'" />
                    <input id="videos" name="videos[]" type="file" class="mt-1 block w-full text-gray-900 dark:text-gray-100" multiple required accept="video/*" />
                    <x-input-error class="mt-2" :messages="$errors->get('videos')" />
                </div>
                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Enviar Vídeos') }}</x-primary-button>
                </div>
            </form>
        @else
            <div class="p-4 mt-6 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-900 dark:text-yellow-300" role="alert">
                @if ($video_limit > 0)
                    <span class="font-medium">Limite de vídeos atingido!</span> Você já tem {{ $video_count }} de {{ $video_limit }} vídeos permitidos.
                @else
                    <span class="font-medium">O seu plano não permite vídeos!</span> Para adicionar vídeos, você pode fazer um upgrade de plano.
                @endif
            </div>
        @endif

        <hr class="my-8 border-gray-200 dark:border-gray-700">
        <div>
            <h3 class="text-md font-medium text-gray-900 dark:text-gray-100">Vídeos Atuais</h3>
            @if($media->where('type', 'video')->isNotEmpty())
                <div class="mt-4 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                     {{-- ======================================================= --}}
                    {{-- ============= INÍCIO DO CÓDIGO RESTAURADO ============== --}}
                    {{-- ======================================================= --}}
                    @foreach($media->where('type', 'video') as $video)
                        <div class="relative group">
                            <video controls class="rounded-lg object-cover w-full h-40 bg-black">
                                <source src="{{ asset('storage/' . $video->path) }}" type="video/mp4">
                                Seu navegador não suporta vídeos.
                            </video>
                            <div class="absolute inset-0 bg-black bg-opacity-20 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-white text-xs font-bold capitalize bg-black bg-opacity-50 px-2 py-1 rounded mb-1">{{ $video->status }}</span>
                                <form method="POST" action="{{ route('galeria.destroy', $video->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white rounded-full p-1 leading-none" onclick="return confirm('Tem certeza que deseja apagar este vídeo?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                     {{-- ======================================================= --}}
                    {{-- =============== FIM DO CÓDIGO RESTAURADO =============== --}}
                    {{-- ======================================================= --}}
                </div>
            @else
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Você ainda não possui vídeos na sua galeria.</p>
            @endif
        </div>
    </div>

    @if (session('status') === 'gallery-updated')
        <div class="p-4 my-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-900 dark:text-green-400" role="alert">
            {{ session('success_message') }}
        </div>
    @endif
</section>

