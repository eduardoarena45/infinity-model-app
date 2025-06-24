<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Informações do Perfil
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Atualize os dados públicos do seu perfil e os serviços que oferece.
        </p>
    </header>

    @if (session('status') === 'perfil-publico-atualizado')
        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">Salvo com sucesso.</p>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- DADOS BÁSICOS, FOTO, LOCALIZAÇÃO, ETC. --}}
        <div>
            <x-input-label for="imagem_principal_url" :value="__('Foto Principal')" />
            @if ($perfil->imagem_principal_url) <img src="{{ Storage::url($perfil->imagem_principal_url) }}" alt="Foto Atual" class="mt-2 w-32 h-32 rounded-md object-cover"> @endif
            <x-text-input id="imagem_principal_url" class="block mt-1 w-full" type="file" name="imagem_principal_url" />
            <x-input-error :messages="$errors->get('imagem_principal_url')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="nome_artistico" :value="__('Nome Artístico')" />
            <x-text-input id="nome_artistico" class="block mt-1 w-full" type="text" name="nome_artistico" :value="old('nome_artistico', $perfil->nome_artistico)" required autofocus />
        </div>
        <div>
            <x-input-label for="data_nascimento" :value="__('Data de Nascimento')" />
            <x-text-input id="data_nascimento" class="block mt-1 w-full" type="date" name="data_nascimento" :value="old('data_nascimento', $perfil->data_nascimento)" required />
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <x-input-label for="cidade" :value="__('Cidade')" />
                <x-text-input id="cidade" class="block mt-1 w-full" type="text" name="cidade" :value="old('cidade', $perfil->cidade)" required />
            </div>
            <div>
                <x-input-label for="estado" :value="__('Estado (UF)')" />
                <x-text-input id="estado" class="block mt-1 w-full" type="text" name="estado" :value="old('estado', $perfil->estado)" required maxlength="2" />
            </div>
        </div>
        <div>
            <x-input-label for="whatsapp" :value="__('WhatsApp')" />
            <x-text-input id="whatsapp" class="block mt-1 w-full" type="text" name="whatsapp" :value="old('whatsapp', $perfil->whatsapp)" required />
        </div>
        <div>
            <x-input-label for="descricao_curta" :value="__('Descrição')" />
            <textarea id="descricao_curta" name="descricao_curta" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">{{ old('descricao_curta', $perfil->descricao_curta) }}</textarea>
        </div>
        <div>
            <x-input-label for="valor_hora" :value="__('Valor por Hora')" />
            <x-text-input id="valor_hora" class="block mt-1 w-full" type="text" name="valor_hora" :value="old('valor_hora', $perfil->valor_hora)" required />
        </div>

        <!-- NOVA SEÇÃO: Seleção de Serviços -->
        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
            <x-input-label :value="__('Serviços Oferecidos')" class="font-medium text-gray-900 dark:text-gray-100" />
            <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($servicosDisponiveis as $servico)
                    <label for="servico_{{ $servico->id }}" class="flex items-center">
                        <input id="servico_{{ $servico->id }}" type="checkbox" name="servicos[]" value="{{ $servico->id }}"
                               @if($perfil->servicos->contains($servico)) checked @endif
                               class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ $servico->nome }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Salvar Tudo') }}</x-primary-button>
        </div>
    </form>
</section>
