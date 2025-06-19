<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolha uma Cidade - Infinity Model</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="text-center p-8 bg-white shadow-lg rounded-lg">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Bem-vindo(a)!</h1>
        <p class="text-gray-600 mb-8">Selecione uma cidade para ver os perfis.</p>

        <div class="space-y-4">
            {{-- Verifica se há cidades cadastradas --}}
            @if($cidades->isNotEmpty())
                {{-- Loop para criar um link para cada cidade --}}
                @foreach ($cidades as $cidade)
                    <a href="{{ route('vitrine.por.cidade', ['cidade' => $cidade->cidade]) }}" class="block w-full max-w-sm mx-auto bg-pink-600 text-white font-bold py-3 px-6 rounded-lg text-lg hover:bg-pink-700 transition-colors">
                        {{ $cidade->cidade }}
                    </a>
                @endforeach
            @else
                <p class="text-gray-500">Nenhuma cidade com perfis disponíveis no momento.</p>
            @endif
        </div>

        <div class="mt-10">
            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700">Acessar painel</a>
        </div>
    </div>
</body>
</html>