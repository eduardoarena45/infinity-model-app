<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Infinity Model') - Processo de Registo</title>

        {{-- Favicons e Estilos --}}
        <link rel="icon" href="{{ asset('favicon/favicon.ico') }}">
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&family=Poppins:wght@700&display=swap" rel="stylesheet">
        <style>
            :root {
                --color-primary: #4E2A51;
            }
            body { font-family: 'Inter', sans-serif; }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="bg-gray-100 dark:bg-gray-900 antialiased">
        <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            {{-- Logo --}}
            <div class="mb-8">
                <a href="/" class="text-4xl font-bold text-gray-800 dark:text-white" style="font-family: 'Poppins', sans-serif;">
                    Infinity Model
                </a>
            </div>

            {{-- Conteúdo da Página (Planos, Pagamento, etc.) --}}
            <main class="w-full">
                @yield('content')
            </main>
        </div>
        @stack('scripts')
    </body>
</html>

