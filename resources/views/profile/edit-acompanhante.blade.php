<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Meu Perfil de Acompanhante') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if (session('status') === 'perfil-publico-atualizado')
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            Perfil atualizado com sucesso!
                        </div>
                    @endif

                    {{--
                    IMPORTANTE: Adicionamos o atributo enctype="multipart/form-data"
                    Isso permite que o formulário envie arquivos (imagens, vídeos, etc.).
                    --}}
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        {{-- Seção de Upload de Foto --}}
                        <div>
                            <x-input-label for="imagem_principal_url" :value="__('Foto Principal')" />
                            {{-- Mostra a foto atual --}}
                            @if ($perfil->imagem_principal_url)
                                <img src="{{ Storage::url($perfil->imagem_principal_url) }}" alt="Foto Atual" class="mt-2 w-32 h-32 rounded-md object-cover">
                            @endif
                            {{-- Campo para fazer o upload de uma nova foto --}}
                            <x-text-input id="imagem_principal_url" class="block mt-1 w-full" type="file" name="imagem_principal_url" />
                            <x-input-error :messages="$errors->get('imagem_principal_url')" class="mt-2" />
                        </div>


                        <!-- Nome Artístico -->
                        <div class="mt-4">
                            <x-input-label for="nome_artistico" :value="__('Nome Artístico')" />
                            <x-text-input id="nome_artistico" class="block mt-1 w-full" type="text" name="nome_artistico" :value="old('nome_artistico', $perfil->nome_artistico)" required autofocus />
                            <x-input-error :messages="$errors->get('nome_artistico')" class="mt-2" />
                        </div>

                        <!-- Data de Nascimento -->
                        <div class="mt-4">
                            <x-input-label for="data_nascimento" :value="__('Data de Nascimento')" />
                            <x-text-input id="data_nascimento" class="block mt-1 w-full" type="date" name="data_nascimento" :value="old('data_nascimento', $perfil->data_nascimento)" required />
                            <x-input-error :messages="$errors->get('data_nascimento')" class="mt-2" />
                        </div>

                        <!-- Cidade -->
                        <div class="mt-4">
                            <x-input-label for="cidade" :value="__('Cidade')" />
                            <x-text-input id="cidade" class="block mt-1 w-full" type="text" name="cidade" :value="old('cidade', $perfil->cidade)" required />
                            <x-input-error :messages="$errors->get('cidade')" class="mt-2" />
                        </div>

                        <!-- WhatsApp -->
                        <div class="mt-4">
                            <x-input-label for="whatsapp" :value="__('WhatsApp (somente números com DDD)')" />
                            <x-text-input id="whatsapp" class="block mt-1 w-full" type="text" name="whatsapp" :value="old('whatsapp', $perfil->whatsapp)" required />
                            <x-input-error :messages="$errors->get('whatsapp')" class="mt-2" />
                        </div>

                        <!-- Descrição -->
                        <div class="mt-4">
                            <x-input-label for="descricao_curta" :value="__('Descrição Curta (fale sobre você)')" />
                            <textarea id="descricao_curta" name="descricao_curta" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('descricao_curta', $perfil->descricao_curta) }}</textarea>
                            <x-input-error :messages="$errors->get('descricao_curta')" class="mt-2" />
                        </div>

                        <!-- Valor por Hora -->
                        <div class="mt-4">
                            <x-input-label for="valor_hora" :value="__('Valor por Hora (ex: 350.50)')" />
                            <x-text-input id="valor_hora" class="block mt-1 w-full" type="text" name="valor_hora" :value="old('valor_hora', $perfil->valor_hora)" required />
                            <x-input-error :messages="$errors->get('valor_hora')" class="mt-2" />
                        </div>


                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Salvar Perfil') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>