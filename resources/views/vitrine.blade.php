<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitrine de Acompanhantes</title>
    <script src="https://cdn.tailwindcss.com"></script> <!-- Usando TailwindCSS para estilo rápido -->
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-center text-gray-800 my-8">Nossos Perfis em Destaque</h1>

        <!-- Grid de Perfis -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($acompanhantes as $perfil)
                <a href="{{ route('vitrine.show', $perfil->id) }}" class="bg-white rounded-lg shadow-md overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <img src="{{ $perfil->imagem_principal_url }}" alt="Foto de {{ $perfil->nome_artistico }}" class="w-full h-80 object-cover">
                    <div class="p-4">
                        <h2 class="text-xl font-semibold text-gray-900">{{ $perfil->nome_artistico }}</h2>
                        <p class="text-gray-600">{{ $perfil->cidade }}, {{ $perfil->estado }}</p>
                        <div class="mt-4 text-lg font-bold text-pink-600">
                            R$ {{ number_format($perfil->valor_hora, 2, ',', '.') }} / hora
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-center col-span-full">Nenhum perfil encontrado.</p>
            @endforelse
        </div>
    </div>
</body>
</html>