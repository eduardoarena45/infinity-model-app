<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Infinity Model')</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>

        {{-- DEFINIÇÃO DA NOVA PALETA DE CORES --}}
        <style>
            :root {
                --color-primary: #4E2A51; /* Roxo Escuro (Ameixa) */
                --color-accent: #B76E79;  /* Dourado Rosé */
                --color-neutral: #36454F; /* Cinza Chumbo */
            }
            body { font-family: 'Inter', sans-serif; }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="bg-gray-100 dark:bg-gray-900 text-[--color-neutral] dark:text-gray-300">
        <nav class="bg-white dark:bg-gray-800 shadow-md sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('cidades.index') }}" class="flex items-center space-x-2 text-2xl font-extrabold text-[--color-primary]">
                            {{-- NOVO LOGÓTIPO EM SVG --}}
                            <img src="{{ asset('images/meu-logo.png') }}" alt="Logotipo Infinity Model" class="h-10 w-auto">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                            </svg>
                            <span>Infinity</span>
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-[--color-accent] transition-colors">Meu Painel</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-[--color-accent] transition-colors">Sair</a>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-[--color-accent] transition-colors">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ml-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-[--color-primary] hover:opacity-90 transition-opacity">
                                    Cadastrar
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>

        <footer class="bg-white dark:bg-gray-800 mt-16">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p class="text-center text-base text-gray-400">&copy; {{ date('Y') }} Infinity Model. Todos os direitos reservados.</p>
                </div>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
        <script>
            Fancybox.bind("[data-fancybox]", { /* ... */ });
        </script>
    </body>
</html>
