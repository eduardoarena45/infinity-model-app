<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Horários de Atendimento
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Defina a sua Disponibilidade Semanal
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Marque os dias em que atende e informe os seus horários. Isto ficará visível no seu perfil público.
                        </p>
                    </header>

                    <form method="post" action="{{ route('disponibilidade.update') }}" class="mt-6">
                        @csrf
                        
                        <div class="space-y-4">
                            @foreach($diasSemana as $dia)
                                <div x-data="{ ativo: {{ isset($horarios[$dia]) ? 'true' : 'false' }} }" class="p-4 border rounded-lg dark:border-gray-700 transition-all" :class="{'bg-blue-50 dark:bg-gray-700/50': ativo}">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" name="ativo[{{ $dia }}]" x-model="ativo" class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-3 text-lg font-medium">{{ ucfirst($dia) }}-feira</span>
                                    </label>
                                    <div x-show="ativo" x-transition class="mt-4 pl-8">
                                        <x-input-label for="horario_{{ $dia }}" value="Horário (Ex: 14h às 22h)" />
                                        {{-- LINHA ALTERADA PARA ADICIONAR O ATRIBUTO REQUIRED --}}
                                        <x-text-input id="horario_{{ $dia }}" name="horario[{{ $dia }}]" type="text" class="mt-1 block w-full" :value="$horarios[$dia]['horario'] ?? ''" ::required="ativo" />
                                        {{-- LINHA ADICIONADA PARA MOSTRAR O ERRO DE VALIDAÇÃO --}}
                                        <x-input-error class="mt-2" :messages="$errors->get('horario.' . $dia)" />
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>{{ __('Salvar Disponibilidade') }}</x-primary-button>
                            @if (session('status') === 'disponibilidade-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600 dark:text-gray-400">{{ __('Salvo.') }}</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
