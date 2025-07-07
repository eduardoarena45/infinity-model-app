<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            A Minha Galeria de Mídia
        </h2>

        {{-- MOSTRA OS LIMITES DO PLANO ATUAL DA UTILIZADORA --}}
        @if(Auth::user()->assinatura && Auth::user()->assinatura->plano)
            @php
                $plano = Auth::user()->assinatura->plano;
                $fotosAtuais = $perfil->midias()->where('tipo', 'foto')->count();
                $videosAtuais = $perfil->midias()->where('tipo', 'video')->count();
            @endphp
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Seu plano: <span class="font-semibold">{{ $plano->nome }}</span>.
                <br>
                Fotos enviadas: {{ $fotosAtuais }} de {{ $plano->limite_fotos }}.
                <br>
                Vídeos enviados: {{ $videosAtuais }} de {{ $plano->limite_videos }}.
            </p>
        @else
             <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Você não possui um plano de assinatura ativo.
            </p>
        @endif
    </header>

    {{-- Exibe uma mensagem de erro se a utilizadora atingir o limite do plano --}}
    @if($errors->has('limite'))
        <div class="mt-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
            {{ $errors->first('limite') }}
        </div>
    @endif

    {{-- Grelha que exibe as mídias existentes (fotos e vídeos) --}}
    <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($perfil->midias as $midia)
            <div class="relative group">
                @if($midia->tipo === 'video')
                    <video class="rounded-lg object-cover w-full h-40" controls>
                        <source src="{{ Storage::url($midia->caminho_arquivo) }}" type="video/mp4">
                    </video>
                @else
                    <img src="{{ Storage::url($midia->caminho_arquivo) }}" class="rounded-lg object-cover w-full h-40" alt="Foto da galeria">
                @endif

                {{-- Formulário com o botão de apagar para cada mídia --}}
                <form method="POST" action="{{ route('galeria.destroy', $midia) }}" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    @csrf
                    @method('delete')
                    <button type="submit" class="p-1.5 bg-red-600 text-white rounded-full leading-none" onclick="return confirm('Tem a certeza que quer apagar esta mídia?')">
                        &times;
                    </button>
                </form>
            </div>
        @endforeach
    </div>

    {{-- Formulário para enviar novas mídias --}}
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
                <p class="text-sm text-gray-600 dark:text-gray-400">Enviado com sucesso! A sua mídia está pendente de aprovação.</p>
            @endif
        </div>
    </form>
</section>
