<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Informações do Perfil
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Atualize os dados públicos do seu perfil, a sua localização e os serviços que oferece.
        </p>
    </header>

    @if (session('status') === 'perfil-publico-atualizado')
        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">Salvo com sucesso.</p>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')
        
        <div>
            <x-input-label for="imagem_principal_url" :value="__('Foto Principal')" />
            @if ($perfil->imagem_principal_url)
                <img src="{{ $perfil->foto_principal_url_completa }}" alt="Foto Atual" class="mt-2 w-32 h-32 rounded-md object-cover">
            @endif
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="estado" :value="__('Estado')" />
                <select name="estado" id="estado" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">
                    <option value="">Selecione um estado...</option>
                    @foreach($estados as $estado)
                        <option value="{{ $estado->estado }}" @if(old('estado', $perfil->estado) == $estado->estado) selected @endif>{{ $estado->estado }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="cidade" :value="__('Cidade')" />
                <select name="cidade" id="cidade" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600" required>
                    <option value="">Selecione um estado primeiro</option>
                </select>
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

        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
            <x-input-label :value="__('Serviços Oferecidos')" class="font-medium text-gray-900 dark:text-gray-100" />
            <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($servicosDisponiveis as $servico)
                    <label for="servico_{{ $servico->id }}" class="flex items-center">
                        <input id="servico_{{ $servico->id }}" type="checkbox" name="servicos[]" value="{{ $servico->id }}" @if($perfil->servicos->contains($servico)) checked @endif class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ $servico->nome }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Salvar Informações') }}</x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const estadoSelect = document.getElementById('estado');
            const cidadeSelect = document.getElementById('cidade');
            const estadoInicial = '{{ old('estado', $perfil->estado) }}';
            const cidadeInicial = '{{ old('cidade', $perfil->cidade) }}';

            function carregarCidades(uf, cidadeSelecionada = null) {
                if (!uf) {
                    cidadeSelect.innerHTML = '<option value="">Selecione um estado primeiro</option>';
                    return;
                }
                cidadeSelect.innerHTML = '<option value="">A carregar cidades...</option>';
                fetch(`/api/cidades/${uf}`)
                    .then(res => res.json())
                    .then(cidades => {
                        cidadeSelect.innerHTML = '<option value="">Selecione uma cidade</option>';
                        cidades.forEach(local => {
                            const option = document.createElement('option');
                            option.value = local.cidade;
                            option.textContent = local.cidade;
                            if (local.cidade === cidadeSelecionada) {
                                option.selected = true;
                            }
                            cidadeSelect.appendChild(option);
                        });
                    });
            }

            estadoSelect.addEventListener('change', function () {
                carregarCidades(this.value);
            });

            if (estadoInicial) {
                carregarCidades(estadoInicial, cidadeInicial);
            }
        });
    </script>
</section>
