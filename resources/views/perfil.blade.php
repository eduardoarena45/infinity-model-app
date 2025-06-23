@php use Illuminate\Support\Facades\Storage; @endphp
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de {{ $acompanhante->nome_artistico }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4 md:p-8">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl mx-auto overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/3"><img src="{{ Storage::url($acompanhante->imagem_principal_url) }}" alt="Foto de {{ $acompanhante->nome_artistico }}" class="w-full h-full object-cover"></div>
                <div class="p-8 md:w-2/3">
                    <h1 class="text-4xl font-bold text-gray-900">{{ $acompanhante->nome_artistico }}</h1>
                    <p class="text-gray-600 text-lg mt-1">{{ $acompanhante->cidade }}, {{ $acompanhante->estado }} - {{ $acompanhante->idade }} anos</p>
                    <p class="text-gray-700 mt-6">{{ $acompanhante->descricao_curta }}</p>
                    <div class="mt-8">
                        <span class="text-gray-500">Valor:</span>
                        <span class="text-3xl font-bold text-pink-600">R$ {{ number_format($acompanhante->valor_hora, 2, ',', '.') }} / hora</span>
                    </div>
                    <a href="https://wa.me/55{{ $acompanhante->whatsapp }}" target="_blank" class="mt-8 inline-block w-full text-center bg-green-500 text-white font-bold py-4 px-6 rounded-lg text-lg hover:bg-green-600 transition-colors">Entrar em Contato por WhatsApp</a>
                    <div class="mt-10">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">Galeria</h3>
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
                    <a href="{{ route('cidades.index') }}" class="mt-8 block text-center text-gray-600 hover:text-gray-800">&larr; Voltar à seleção de cidades</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>