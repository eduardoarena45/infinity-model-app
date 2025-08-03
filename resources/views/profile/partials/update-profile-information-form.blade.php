<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Informações do Perfil
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Atualize os dados públicos do seu perfil, a sua localização e os serviços que oferece.
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Foto Principal --}}
        <div>
            <x-input-label for="foto_principal" value="Foto Principal (Pública)" />
            <input id="foto_principal" name="foto_principal" type="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
            <x-input-error class="mt-2" :messages="$errors->get('foto_principal')" />
        </div>

        {{-- Foto de Verificação --}}
        <div class="border-t border-b border-gray-200 dark:border-gray-700 py-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Verificação de Identidade (Privado)
            </h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Para sua segurança, envie uma foto nítida do seu rosto segurando seu documento de identidade (RG ou CNH). Esta imagem é confidencial e será vista apenas pela administração.
            </p>
            <div class="mt-4">
                <x-input-label for="foto_verificacao" value="Foto de Verificação com Documento" />
                <input id="foto_verificacao" name="foto_verificacao" type="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                @if($acompanhante->foto_verificacao_path)
                    <p class="mt-2 text-sm text-green-600">✓ Um documento já foi enviado. Enviar um novo irá substituir o anterior.</p>
                @endif
                <x-input-error class="mt-2" :messages="$errors->get('foto_verificacao')" />
            </div>
        </div>

        {{-- Nome Artístico --}}
        <div>
            <x-input-label for="nome_artistico" value="Nome Artístico" />
            <x-text-input id="nome_artistico" name="nome_artistico" type="text" class="mt-1 block w-full" :value="old('nome_artistico', $acompanhante->nome_artistico)" required autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('nome_artistico')" />
        </div>

        {{-- Gênero --}}
        <div>
            <x-input-label for="genero" value="Gênero" />
            <select id="genero" name="genero" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                <option value="">Selecione...</option>
                <option value="mulher" @if(old('genero', $acompanhante->genero) == 'mulher') selected @endif>Mulher</option>
                <option value="homem" @if(old('genero', $acompanhante->genero) == 'homem') selected @endif>Homem</option>
                <option value="trans" @if(old('genero', $acompanhante->genero) == 'trans') selected @endif>Trans</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('genero')" />
        </div>

        {{-- Data de Nascimento --}}
        <div>
            <x-input-label for="data_nascimento" value="Data de Nascimento" />
            <x-text-input id="data_nascimento" name="data_nascimento" type="date" class="mt-1 block w-full" :value="old('data_nascimento', $acompanhante->data_nascimento ? $acompanhante->data_nascimento->format('Y-m-d') : '')" required />
            <x-input-error class="mt-2" :messages="$errors->get('data_nascimento')" />
        </div>

        {{-- Estado --}}
        <div>
            <x-input-label for="estado_id" value="Estado" />
            <select id="estado_id" name="estado_id" required class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full">
                <option value="">Selecione um Estado</option>
                @foreach ($estados as $estado)
                    <option value="{{ $estado->id }}" {{ old('estado_id', $acompanhante->cidade->estado_id ?? '') == $estado->id ? 'selected' : '' }}>
                        {{ $estado->nome }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('estado_id')" />
        </div>

        {{-- Cidade --}}
        <div>
            <x-input-label for="cidade_id" value="Cidade" />
            <select id="cidade_id" name="cidade_id" required class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" {{ !$acompanhante->cidade_id ? 'disabled' : '' }}>
                <option value="">Selecione um estado primeiro</option>
                 @if ($cidades)
                      @foreach ($cidades as $cidade)
                          <option value="{{ $cidade->id }}" {{ old('cidade_id', $acompanhante->cidade_id) == $cidade->id ? 'selected' : '' }}>
                              {{ $cidade->nome }}
                          </option>
                      @endforeach
                 @endif
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('cidade_id')" />
        </div>

        {{-- WhatsApp --}}
        <div>
            <x-input-label for="whatsapp" value="WhatsApp" />
            <x-text-input id="whatsapp" name="whatsapp" type="text" class="mt-1 block w-full" :value="old('whatsapp', $acompanhante->whatsapp)" required />
            <x-input-error class="mt-2" :messages="$errors->get('whatsapp')" />
        </div>

        {{-- Descrição com Limite Dinâmico --}}
        <div>
            @php
                // A variável $descricaoLimit vem do ProfileController@edit
                $label = 'Descrição';
                if (isset($descricaoLimit)) {
                    $label .= " (Limite: {$descricaoLimit} caracteres)";
                } else {
                    $label .= " (Ilimitado)";
                }
            @endphp
            <x-input-label for="descricao" :value="$label" />
            
            <div x-data="{ count: {{ strlen(old('descricao', $acompanhante->descricao)) }}, limit: {{ $descricaoLimit ?? 'null' }} }">
                <textarea id="descricao" name="descricao" required
                    x-on:input="count = $event.target.value.length"
                    @if(isset($descricaoLimit)) maxlength="{{ $descricaoLimit }}" @endif
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                >{{ old('descricao', $acompanhante->descricao) }}</textarea>
                
                <p x-show="limit" class="text-right text-sm text-gray-500 mt-1" :class="{ 'text-red-500': count > limit }">
                    <span x-text="count"></span> / <span x-text="limit"></span>
                </p>
            </div>
            
            <x-input-error class="mt-2" :messages="$errors->get('descricao')" />
        </div>

        {{-- Valor por Hora (Opcional) --}}
        <div>
            <x-input-label for="valor_hora" value="Valor por Hora (Opcional, Ex: 150.00)" />
            <x-text-input id="valor_hora" name="valor_hora" type="text" class="mt-1 block w-full" :value="old('valor_hora', $acompanhante->valor_hora)" />
            <x-input-error class="mt-2" :messages="$errors->get('valor_hora')" />
        </div>

        {{-- Preços Adicionais (Opcional) --}}
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Preços Adicionais (Opcional)
            </h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Preencha apenas os valores que desejar oferecer.
            </p>
            <div class="mt-4">
                <x-input-label for="valor_15_min" :value="__('Valor por 15 Minutos')" />
                <x-text-input id="valor_15_min" name="valor_15_min" type="number" step="0.01" class="mt-1 block w-full" :value="old('valor_15_min', $acompanhante?->valor_15_min)" placeholder="Ex: 100.00" />
                <x-input-error class="mt-2" :messages="$errors->get('valor_15_min')" />
            </div>
            <div class="mt-4">
                <x-input-label for="valor_30_min" :value="__('Valor por 30 Minutos')" />
                <x-text-input id="valor_30_min" name="valor_30_min" type="number" step="0.01" class="mt-1 block w-full" :value="old('valor_30_min', $acompanhante?->valor_30_min)" placeholder="Ex: 150.00" />
                <x-input-error class="mt-2" :messages="$errors->get('valor_30_min')" />
            </div>
            <div class="mt-4">
                <x-input-label for="valor_pernoite" :value="__('Valor por Pernoite')" />
                <x-text-input id="valor_pernoite" name="valor_pernoite" type="number" step="0.01" class="mt-1 block w-full" :value="old('valor_pernoite', $acompanhante?->valor_pernoite)" placeholder="Ex: 1200.00" />
                <x-input-error class="mt-2" :messages="$errors->get('valor_pernoite')" />
            </div>
        </div>

        {{-- Serviços Oferecidos (Opcional) --}}
        <div>
            <x-input-label value="Serviços Oferecidos" />
            <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach ($servicos as $servico)
                    <label for="servico_{{ $servico->id }}" class="flex items-center">
                        <input id="servico_{{ $servico->id }}" name="servicos[]" type="checkbox" value="{{ $servico->id }}"
                               class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                               {{ in_array($servico->id, old('servicos', $servicosAtuais)) ? 'checked' : '' }}>
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ $servico->nome }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Salvar') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600 dark:text-gray-400">{{ __('Salvo.') }}</p>
            @endif
        </div>
    </form>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const estadoSelect = document.getElementById('estado_id');
        const cidadeSelect = document.getElementById('cidade_id');

        estadoSelect.addEventListener('change', function () {
            const estadoId = this.value;
            cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
            cidadeSelect.disabled = true;

            if (!estadoId) {
                cidadeSelect.innerHTML = '<option value="">Selecione um estado primeiro</option>';
                return;
            }

            fetch(`/api/cidades/${estadoId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Erro na resposta da rede');
                    return response.json();
                })
                .then(data => {
                    cidadeSelect.innerHTML = '<option value="">Selecione uma Cidade</option>';
                    data.forEach(cidade => {
                        const option = document.createElement('option');
                        option.value = cidade.id;
                        option.textContent = cidade.nome;
                        cidadeSelect.appendChild(option);
                    });
                    cidadeSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Erro ao buscar cidades:', error);
                    cidadeSelect.innerHTML = '<option value="">Erro ao carregar cidades</option>';
                });
        });
    });
</script>
