{{--
Adicionamos esta linha no topo para importar a classe Storage,
que nos permite gerar URLs públicas para nossos arquivos.
--}}
@php
    use Illuminate\Support\Facades\Storage;
@endphp

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- O título da página agora usa o nome artístico do perfil --}}
    <title>Perfil de {{ $acompanhante->nome_artistico }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4 md:p-8">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl mx-auto overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/3">
                    {{--
                    LINHA MODIFICADA:
                    Usamos Storage::url() para gerar o link correto para a imagem que foi enviada pelo painel.
                    --}}
                    <img src="{{ Storage::url($acompanhante->imagem_principal_url) }}" alt="Foto de {{ $acompanhante->nome_artistico }}" class="w-full h-full object-cover">
                </div>
                <div class="p-8 md:w-2/3">
                    <h1 class="text-4xl font-bold text-gray-900">{{ $acompanhante->nome_artistico }}</h1>
                    {{-- Usando o atributo mágico 'idade' que calcula a idade automaticamente --}}
                    <p class="text-gray-600 text-lg mt-1">{{ $acompanhante->cidade }}, {{ $acompanhante->estado }} - {{ $acompanhante->idade }} anos</p>

                    <p class="text-gray-700 mt-6">{{ $acompanhante->descricao_curta }}</p>

                    <div class="mt-8">
                        <span class="text-gray-500">Valor:</span>
                        <span class="text-3xl font-bold text-pink-600">R$ {{ number_format($acompanhante->valor_hora, 2, ',', '.') }} / hora</span>
                    </div>

                    {{-- Link para o WhatsApp, que agora pega o número do banco de dados --}}
                    <a href="https://wa.me/55{{ $acompanhante->whatsapp }}" target="_blank" class="mt-8 inline-block w-full text-center bg-green-500 text-white font-bold py-4 px-6 rounded-lg text-lg hover:bg-green-600 transition-colors">
                        Entrar em Contato por WhatsApp
                    </a>

                    {{-- Link para voltar para a página inicial (vitrine) --}}
                     <a href="{{ route('vitrine.index') }}" class="mt-4 block text-center text-gray-600 hover:text-gray-800">
                        &larr; Voltar para a vitrine
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
