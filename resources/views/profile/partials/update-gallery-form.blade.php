<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Minha Galeria de Mídia</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Envie novas fotos ou vídeos (máx. 10MB).</p>
    </header>
    <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($perfil->midias as $midia)
        <div class="relative group">
            @if($midia->tipo === 'video')
            <video class="rounded-lg object-cover w-full h-40" controls><source src="{{ Storage::url($midia->caminho_arquivo) }}" type="video/mp4"></video>
            @else
            <img src="{{ Storage::url($midia->caminho_arquivo) }}" class="rounded-lg object-cover w-full h-40" alt="Foto da galeria">
            @endif
            <form method="POST" action="{{ route('galeria.destroy', $midia) }}" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                @csrf @method('delete')
                <button type="submit" class="p-1.5 bg-red-600 text-white rounded-full leading-none">&times;</button>
            </form>
        </div>
        @endforeach
    </div>
    <form method="POST" action="{{ route('galeria.upload') }}" enctype="multipart/form-data" class="mt-6">
        @csrf
        <div>
            <x-input-label for="midias" value="Adicionar Novas Fotos ou Vídeos" />
            <x-text-input id="midias" name="midias[]" type="file" class="mt-1 block w-full" multiple accept="image/*,video/*"/>
            <x-input-error class="mt-2" :messages="$errors->get('midias.*')" />
        </div>
        <div class="flex items-center gap-4 mt-4">
            <x-primary-button>{{ __('Enviar Mídia') }}</x-primary-button>
            @if (session('status') === 'galeria-atualizada')
            <p class="text-sm text-gray-600 dark:text-gray-400">Enviado!</p>
            @endif
        </div>
    </form>
</section>