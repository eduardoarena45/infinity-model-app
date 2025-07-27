<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Infinity Model')</title>

        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
        <link rel="shortcut icon" href="{{ asset('favicon/favicon.ico') }}">
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&family=Poppins:wght@700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>

        <style>
            :root {
                --color-primary: #4E2A51; /* Roxo Escuro/Vinho */
                --color-accent: #B76E79;  /* Rosa Queimado */
                --color-neutral: #36454F; /* Grafite */
            }
            body { font-family: 'Inter', sans-serif; }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="bg-gray-100 dark:bg-gray-900 text-[--color-neutral] dark:text-gray-300">
        
        {{-- AVISO DE IDADE --}}
        <div id="age-gate-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-90 backdrop-blur-sm flex items-center justify-center z-50 transition-opacity duration-300">
            <div class="bg-white dark:bg-slate-800 p-8 rounded-xl shadow-2xl text-center max-w-md mx-4 border border-gray-200 dark:border-slate-700 transform transition-all scale-95 opacity-0" id="age-gate-modal">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/50 mb-4">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Confirmação de Idade</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-8">
                    Este site contém material destinado a adultos. <br>Para continuar, você deve ter **18 anos** ou mais.
                </p>
                <div class="space-y-4">
                    <button id="confirm-age-btn" class="w-full text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105" style="background-color: var(--color-primary);">
                        Tenho 18 anos ou mais - Entrar
                    </button>
                    <a href="https://www.google.com" class="w-full block bg-gray-200 dark:bg-slate-700 text-gray-800 dark:text-gray-300 font-bold py-3 px-6 rounded-lg hover:opacity-80 transition-opacity">
                        Sair do Site
                    </a>
                </div>
            </div>
        </div>
    
        {{-- NAVEGAÇÃO --}}
        <nav class="bg-white dark:bg-gray-800 shadow-md sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('cidades.index') }}" class="flex items-center">
                            <span class="text-2xl font-bold text-gray-800 dark:text-white" style="font-family: 'Poppins', sans-serif;">
                                Infinity Model
                            </span>
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
                                {{-- BOTÃO CORRIGIDO PARA AZUL --}}
                                <a href="{{ route('register') }}" class="ml-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-opacity">
                                    Cadastrar
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        {{-- CONTEÚDO PRINCIPAL DA PÁGINA --}}
        <main>
            @yield('content')
        </main>

        {{-- RODAPÉ --}}
        <footer class="bg-white dark:bg-gray-800 mt-16">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-center space-x-6">
                    <a href="{{ route('termos') }}" class="text-sm text-gray-500 hover:text-gray-900 dark:hover:text-white">Termos de Serviço</a>
                    <a href="{{ route('privacidade') }}" class="text-sm text-gray-500 hover:text-gray-900 dark:hover:text-white">Política de Privacidade</a>
                </div>
                <div class="mt-8 text-center">
                    <p class="text-center text-base text-gray-400">&copy; {{ date('Y') }} Infinity Model. Todos os direitos reservados.</p>
                </div>
            </div>
        </footer>

        {{-- SCRIPTS --}}
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
        <script>
            Fancybox.bind("[data-fancybox]", {});

            document.addEventListener('DOMContentLoaded', function() {
                const overlay = document.getElementById('age-gate-overlay');
                const modal = document.getElementById('age-gate-modal');
                const confirmBtn = document.getElementById('confirm-age-btn');
                const ageVerified = sessionStorage.getItem('ageVerified');

                if (ageVerified === 'true') {
                    overlay.style.display = 'none';
                } else {
                    setTimeout(() => {
                        modal.style.transform = 'scale(1)';
                        modal.style.opacity = '1';
                    }, 100);
                }

                confirmBtn.addEventListener('click', function() {
                    overlay.style.opacity = '0';
                    setTimeout(() => {
                        overlay.style.display = 'none';
                    }, 300);
                    sessionStorage.setItem('ageVerified', 'true');
                });
            });
        </script>
        @stack('scripts')
    </body>
</html>