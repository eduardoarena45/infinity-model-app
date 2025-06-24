@extends('layouts.public')
@section('title', "Perfil de {$acompanhante->nome_artistico}")

@section('content')
    @php use Illuminate\Support\Facades\Storage; @endphp
    <div class="container mx-auto p-4 md:p-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-4xl mx-auto overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/3"><img src="{{ Storage::url($acompanhante->imagem_principal_url) }}" alt="Foto de {{ $acompanhante->nome_artistico }}" class="w-full h-full object-cover"></div>
                <div class="p-8 md:w-2/3">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-x-2">
    <span>{{ $acompanhante->nome_artistico }}</span>
    {{-- MOSTRA O SELO SE O PERFIL FOR VERIFICADO --}}
    @if($acompanhante->is_verified)
        <span title="Perfil Verificado">
            <svg class="w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a.75.75 0 00-1.06-1.06L9 10.94l-1.72-1.72a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.06 0l3.75-3.75z" clip-rule="evenodd" />
            </svg>
        </span>
    @endif
</h1>
                    <p class="text-gray-600 dark:text-gray-400 text-lg mt-1">{{ $acompanhante->cidade }}, {{ $acompanhante->estado }} - {{ $acompanhante->idade }} anos</p>
                    <p class="text-gray-700 dark:text-gray-300 mt-6">{{ $acompanhante->descricao_curta }}</p>

                    <!-- NOVA SEÇÃO DE SERVIÇOS -->
                    @if($acompanhante->servicos->isNotEmpty())
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Serviços</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($acompanhante->servicos as $servico)
                                    <span class="inline-block bg-pink-100 text-pink-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-pink-900 dark:text-pink-300">
                                        {{ $servico->nome }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-8"><span class="text-gray-500">Valor:</span> <span class="text-3xl font-bold text-pink-600">R$ {{ number_format($acompanhante->valor_hora, 2, ',', '.') }} / hora</span></div>
                    <a href="https://wa.me/55{{ $acompanhante->whatsapp }}" target="_blank" class="mt-8 inline-block w-full text-center bg-green-500 text-white font-bold py-4 px-6 rounded-lg text-lg hover:bg-green-600 transition-colors">Entrar em Contato por WhatsApp</a>

                    <div class="mt-10">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 border-b pb-2">Galeria</h3>
                        @if($acompanhante->midias->isNotEmpty())
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                @foreach($acompanhante->midias as $midia)
                                    @if($midia->tipo === 'video')
                                        <div class="rounded-lg overflow-hidden"><video class="w-full h-48 object-cover" controls><source src="{{ Storage::url($midia->caminho_arquivo) }}" type="video/mp4"></video></div>
                                    @else
                                        <a href="{{ Storage::url($midia->caminho_arquivo) }}" target="_blank"><img src="{{ Storage::url($midia->caminho_arquivo) }}" class="rounded-lg object-cover w-full h-48 hover:opacity-80 transition-opacity" alt="Foto da galeria"></a>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">Nenhuma foto ou vídeo na galeria ainda.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection