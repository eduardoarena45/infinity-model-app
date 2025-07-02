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
                        @if($acompanhante->is_verified)
                            <span title="Perfil Verificado"><svg class="w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a.75.75 0 00-1.06-1.06L9 10.94l-1.72-1.72a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.06 0l3.75-3.75z" clip-rule="evenodd" /></svg></span>
                        @endif
                    </h1>
                    <div class="flex items-center mt-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $acompanhante->nota_media ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        @endfor
                        <span class="ml-2 text-sm text-gray-500">{{ $acompanhante->nota_media }} de 5 ({{ $acompanhante->avaliacoes->count() }} avaliações)</span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-lg mt-1">{{ $acompanhante->cidade }}, {{ $acompanhante->estado }} - {{ $acompanhante->idade }} anos</p>
                    <p class="text-gray-700 dark:text-gray-300 mt-6">{{ $acompanhante->descricao_curta }}</p>

                    <!-- SECÇÃO DA GALERIA (RESTAURADA) -->
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

                    <!-- SECÇÃO DE AVALIAÇÕES -->
                    <div class="mt-10 border-t pt-8">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Deixe a sua Avaliação</h3>
                        @if(session('success'))
                            <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">{{ session('success') }}</div>
                        @endif
                        <form action="{{ route('avaliacoes.store', $acompanhante) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="nome_autor" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Seu Nome</label>
                                <input type="text" name="nome_autor" id="nome_autor" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sua Nota</label>
                                <div class="flex items-center mt-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                    <label class="mr-4"><input type="radio" name="nota" value="{{ $i }}" class="sr-only peer"><svg class="w-8 h-8 cursor-pointer text-gray-300 peer-checked:text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg></label>
                                    @endfor
                                </div>
                            </div>
                            <div>
                                <label for="comentario" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Seu Comentário (opcional)</label>
                                <textarea name="comentario" id="comentario" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600"></textarea>
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-pink-600 hover:bg-pink-700">Enviar Avaliação</button>
                        </form>
                        <div class="mt-12 space-y-6">
                            <h4 class="text-xl font-bold text-gray-800 dark:text-white">O que outros dizem</h4>
                            @forelse($acompanhante->avaliacoes as $avaliacao)
                                <div class="border-t pt-4">
                                    <div class="flex items-center mb-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $avaliacao->nota ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        @endfor
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-400 italic">"{{ $avaliacao->comentario }}"</p>
                                    <p class="text-right text-sm text-gray-500 mt-2">- {{ $avaliacao->nome_autor }}, {{ $avaliacao->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <p class="text-gray-500">Este perfil ainda não tem avaliações. Seja o primeiro a deixar uma!</p>
                            @endforelse
                        </div>
                    </div>

                    <a href="{{ route('cidades.index') }}" class="mt-8 block text-center text-gray-600 hover:text-gray-800">&larr; Voltar à seleção de cidades</a>
                </div>
            </div>
        </div>
    </div>
@endsection
