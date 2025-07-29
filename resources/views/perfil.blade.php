@extends('layouts.public')

@section('title', "Perfil de {$acompanhante->nome_artistico}")

@section('content')

<div class="bg-gray-100 dark:bg-gray-900 py-12">
    <div class="container mx-auto px-4">

        {{-- BOTÃO VOLTAR NO TOPO --}}
        <div class="max-w-4xl mx-auto mb-4">
            <a href="javascript:history.back()" class="inline-flex items-center text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                Voltar para a vitrine
            </a>
        </div>

        <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden">
            <div class="relative">
                <div class="h-48 bg-gray-200 dark:bg-gray-700 bg-cover bg-center" style="background-image: url('{{ $acompanhante->foto_principal_url }}');">
                    <div class="h-full w-full bg-black bg-opacity-50 backdrop-blur-md"></div>
                </div>
                <div class="absolute top-24 left-1/2 -translate-x-1/2 md:left-12 md:-translate-x-0">
                    <a href="{{ $acompanhante->foto_principal_url }}" data-fancybox="gallery" data-caption="{{ $acompanhante->nome_artistico }}">
                        <img src="{{ $acompanhante->foto_principal_url }}" alt="Foto de {{ $acompanhante->nome_artistico }}" class="w-32 h-32 md:w-40 md:h-40 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-lg">
                    </a>
                </div>
            </div>

            <div class="pt-20 md:pt-8 pb-8 px-4 sm:px-8">
                <div class="text-center md:text-left md:ml-48">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center justify-center md:justify-start gap-x-2">
                        <span>{{ $acompanhante->nome_artistico }}</span>
                        @if($acompanhante->is_verified)
                            <span title="Perfil Verificado"><svg class="w-7 h-7 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a.75.75 0 00-1.06-1.06L9 10.94l-1.72-1.72a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.06 0l3.75-3.75z" clip-rule="evenodd" /></svg></span>
                        @endif
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400 text-lg mt-1">{{ $acompanhante->cidade->nome ?? 'Cidade não informada' }}, {{ $acompanhante->idade }} anos</p>
                </div>
                <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="text-center">
                        <span class="text-gray-500 dark:text-gray-400">Valor</span>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400">R$ {{ number_format($acompanhante->valor_hora, 2, ',', '.') }} / hora</p>
                    </div>
                    <a href="https://wa.me/55{{ preg_replace('/\D/', '', $acompanhante->whatsapp) }}" target="_blank" class="w-full md:w-auto flex items-center justify-center bg-green-500 text-white font-bold py-3 px-8 rounded-lg text-lg hover:bg-green-600 transition-colors shadow-lg">
                        <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
                        <span>Contato por WhatsApp</span>
                    </a>
                </div>
            </div>

            <div class="p-4 sm:p-8 space-y-12">
                <section>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 border-b dark:border-gray-700 pb-2">Sobre mim</h3>
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $acompanhante->descricao }}</p>
                </section>

                @if($acompanhante->servicos->isNotEmpty())
                <section>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 border-b dark:border-gray-700 pb-2">Serviços</h3>
                    <div class="flex flex-wrap gap-3">
                        @foreach($acompanhante->servicos as $servico)
                            <span class="text-gray-300 text-sm font-medium px-3 py-1 border border-gray-600 rounded-full">{{ $servico->nome }}</span>
                        @endforeach
                    </div>
                </section>
                @endif
                
                <section>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 border-b dark:border-gray-700 pb-2">Galeria</h3>
                    @if($acompanhante->midias->where('status', 'aprovado')->isNotEmpty())
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2 sm:gap-4">
                            @foreach($acompanhante->midias->where('status', 'aprovado') as $midia)
                                @if($midia->type === 'image')
                                    <a href="{{ Storage::url($midia->path) }}" data-fancybox="gallery" data-caption="{{ $acompanhante->nome_artistico }}">
                                        <img src="{{ Storage::url($midia->path) }}" class="rounded-lg object-cover w-full h-48 hover:opacity-80 transition-opacity shadow-md" alt="Foto da galeria">
                                    </a>
                                @elseif($midia->type === 'video' && $midia->thumbnail_path)
                                    <a href="{{ Storage::url($midia->path) }}" data-fancybox="gallery" data-caption="{{ $acompanhante->nome_artistico }}">
                                        <div class="relative w-full h-48 bg-black rounded-lg shadow-md group">
                                            <img src="{{ Storage::url($midia->thumbnail_path) }}" class="w-full h-full object-cover rounded-lg group-hover:opacity-80 transition-opacity" alt="Capa do vídeo">
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <div class="bg-black bg-opacity-50 rounded-full p-3">
                                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path></svg>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center">Nenhuma mídia na galeria ainda.</p>
                    @endif
                </section>
                
                <section>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 border-b dark:border-gray-700 pb-2">Avaliações de Clientes</h3>
                    @if(session('success'))
                        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">{{ session('success') }}</div>
                    @endif
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 sm:p-6 rounded-lg mb-8">
                        <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Deixe sua avaliação anônima</h4>
                        <form action="{{ route('avaliacoes.store', $acompanhante->id) }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label for="nome_avaliador" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Seu Nome (será público)</label>
                                    <input type="text" name="nome_avaliador" id="nome_avaliador" required maxlength="50" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('nome_avaliador') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="comentario" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Seu Comentário</label>
                                    <textarea name="comentario" id="comentario" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                    @error('comentario') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                     <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sua Nota</label>
                                     <div class="flex flex-row-reverse justify-end items-center">
                                         @for ($i = 5; $i >= 1; $i--)
                                             <input type="radio" id="nota-{{$i}}" name="nota" value="{{$i}}" class="sr-only peer" required>
                                             <label for="nota-{{$i}}" class="text-gray-300 dark:text-gray-600 cursor-pointer text-3xl peer-hover:text-yellow-400 peer-checked:text-yellow-400 hover:text-yellow-400">★</label>
                                         @endfor
                                     </div>
                                     @error('nota') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Enviar Avaliação</button>
                            </div>
                        </form>
                    </div>

                    {{-- ======================================================= --}}
                    {{-- === INÍCIO DAS ALTERAÇÕES PARA PAGINAÇÃO === --}}
                    {{-- ======================================================= --}}
                    <div class="space-y-6">
                        @forelse($avaliacoes as $avaliacao)
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                                <div class="flex items-center mb-2">
                                    <p class="font-bold text-gray-900 dark:text-white">{{ $avaliacao->nome_avaliador }}</p>
                                    <div class="flex items-center ml-4">
                                         @for ($i = 1; $i <= 5; $i++)
                                             <svg class="w-5 h-5 {{ $i <= $avaliacao->nota ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                         @endfor
                                    </div>
                                </div>
                                <p class="text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $avaliacao->comentario }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400 text-center py-4">Este perfil ainda não tem avaliações. Seja o primeiro a comentar!</p>
                        @endforelse
                    </div>

                    {{-- Adiciona os links de paginação --}}
                    <div class="mt-8">
                        {{ $avaliacoes->links() }}
                    </div>
                    {{-- ======================================================= --}}
                    {{-- === FIM DAS ALTERAÇÕES === --}}
                    {{-- ======================================================= --}}
                </section>
            </div>
        </div>
        
        <div class="text-center mt-8">
            <a href="javascript:history.back()" class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white">&larr; Voltar</a>
        </div>
    </div>
</div>
@endsection
