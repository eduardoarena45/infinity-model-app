<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- ======================================================= --}}
    {{-- ===================== SEO BÁSICO ======================= --}}
    {{-- ======================================================= --}}
    <title>@yield('title', config('app.name', 'Infinity Model'))</title>
    <meta name="description" content="@yield('description', 'Encontre acompanhantes em todo o Brasil — Mulheres, homens e trans em diversas cidades. Perfis verificados e fotos reais.')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- ======================================================= --}}
    {{-- ================== OPEN GRAPH (FACEBOOK) =============== --}}
    {{-- ======================================================= --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Infinity Model">
    <meta property="og:title" content="@yield('title', 'Infinity Model — Encontre acompanhantes no Brasil')">
    <meta property="og:description" content="@yield('description', 'Veja acompanhantes reais e verificados em várias cidades do Brasil.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('image', asset('images/og-image.jpg'))">

    {{-- ======================================================= --}}
    {{-- ===================== TWITTER CARDS =================== --}}
    {{-- ======================================================= --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Infinity Model — Acompanhantes de Luxo')">
    <meta name="twitter:description" content="@yield('description', 'Perfis reais, fotos verificadas e acompanhantes disponíveis em várias cidades.')">
    <meta name="twitter:image" content="@yield('image', asset('images/og-image.jpg'))">

    {{-- ======================================================= --}}
    {{-- ===================== FAVICONS ========================= --}}
    {{-- ======================================================= --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
    <link rel="shortcut icon" href="{{ asset('favicon/favicon.ico') }}">

    {{-- ======================================================= --}}
    {{-- ===================== ASSETS =========================== --}}
    {{-- ======================================================= --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- ======================================================= --}}
    {{-- ===================== ESTILOS GERAIS =================== --}}
    {{-- ======================================================= --}}
    <style>
        :root {
            --color-primary: #4E2A51;
            --color-accent: #B76E79;
            --color-neutral: #36454F;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>

    <body class="font-sans antialiased">
        <div x-data="{ mobileMenuOpen: false }" class="min-h-screen bg-gray-100 dark:bg-gray-900">

            @php
                // Otimizamos para carregar a acompanhante e a sua cidade de uma só vez.
                $acompanhante = Auth::user()->loadMissing('acompanhante.cidade')->acompanhante;
                $unreadNotifications = Auth::user()->unreadNotifications;
            @endphp

            <!-- CABEÇALHO PRINCIPAL (HEADER) -->
            <header class="bg-white dark:bg-gray-800 shadow-md sticky top-0 z-40">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">

                        <!-- Lado Esquerdo: Logo e Navegação Desktop -->
                        <div class="flex items-center space-x-8">
                            <!-- Logo -->
                            {{-- ======================================================= --}}
                            {{-- =================== INÍCIO DA ALTERAÇÃO ================== --}}
                            {{-- ======================================================= --}}

                            {{-- Condição mais robusta, que verifica também se a cidade existe --}}
                            @if ($acompanhante && $acompanhante->cidade && $acompanhante->status === 'aprovado' && $acompanhante->isPubliclyReady())
                                {{-- O link agora leva para a VITRINE DA CIDADE e abre na MESMA ABA --}}
                                <a href="{{ route('acompanhantes.por.cidade', ['genero' => $acompanhante->genero, 'cidade' => $acompanhante->cidade->nome]) }}" title="Ver a vitrine da minha cidade" class="text-2xl font-bold text-gray-800 dark:text-white">
                                    Infinity Model
                                </a>
                            @else
                                {{-- O comportamento padrão continua a ser levar para o dashboard --}}
                                <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-gray-800 dark:text-white">
                                    Infinity Model
                                </a>
                            @endif

                            {{-- ======================================================= --}}
                            {{-- ==================== FIM DA ALTERAÇÃO ===================== --}}
                            {{-- ======================================================= --}}

                            <!-- Navegação Principal (só aparece em ecrãs médios para cima) -->
                            <nav class="hidden md:flex space-x-4">
                                @php
                                    $navLinks = [
                                        'dashboard' => 'Desempenho',
                                        'profile.edit' => 'Editar Perfil',
                                        'galeria.gerir' => 'Galeria',
                                        'disponibilidade.edit' => 'Disponibilidade',
                                        'planos.selecionar' => 'Meu Plano',
                                    ];
                                @endphp
                                @foreach ($navLinks as $route => $label)
                                    <a href="{{ route($route) }}"
                                       class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs($route) ? 'bg-blue-600 text-white' : 'text-gray-500 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ $label }}
                                    </a>
                                @endforeach
                            </nav>
                        </div>

                        <!-- Lado Direito: Notificações, Avatar e Botão Mobile -->
                        <div class="flex items-center space-x-4">
                            <!-- Bloco de Notificações -->
                            <div x-data="{ open: false, unreadCount: {{ $unreadNotifications->count() }} }" class="relative">
                                <button @click="open = !open; if(open && unreadCount > 0) { markNotificationsAsRead(); unreadCount = 0; }" class="relative p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                    </svg>
                                    <template x-if="unreadCount > 0">
                                        <span class="absolute top-0 right-0 h-4 w-4 flex items-center justify-center rounded-full bg-red-500 text-white text-xs" x-text="unreadCount"></span>
                                    </template>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-md shadow-lg overflow-hidden z-20" x-cloak>
                                    <div class="py-2 px-4 border-b dark:border-gray-700">
                                        <h3 class="font-bold text-gray-900 dark:text-white">Notificações</h3>
                                    </div>
                                    <div class="divide-y divide-gray-100 dark:divide-gray-700 max-h-96 overflow-y-auto">
                                        @forelse($unreadNotifications as $notification)
                                            <div class="p-4 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $notification->data['message'] ?? 'Você tem uma nova notificação.' }}
                                                <div class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                                            </div>
                                        @empty
                                            <div class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">Nenhuma notificação nova.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <!-- Formulário do Avatar -->
                            <form id="avatar-form" action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label for="avatar-upload-input" class="cursor-pointer">
                                    <img id="avatar-preview" class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->private_avatar_url }}" alt="{{ Auth::user()->name }}">
                                </label>
                                <input type="file" id="avatar-upload-input" name="avatar" class="hidden" accept="image/*" onchange="document.getElementById('avatar-form').submit()">
                            </form>

                            <!-- Dropdown do Usuário -->
                            <div class="relative">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition">
                                            <span class="hidden sm:inline">{{ $acompanhante->nome_artistico ?? Auth::user()->name }}</span>
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Sair</x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>

                            <!-- Botão Hambúrguer (Mobile) -->
                            <div class="md:hidden">
                                <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none">
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                        <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Menu Gaveta (Mobile) -->
                <div x-show="mobileMenuOpen" class="md:hidden" x-cloak>
                    <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                        @foreach ($navLinks as $route => $label)
                            <a href="{{ route($route) }}"
                               class="block px-3 py-2 rounded-md text-base font-medium transition-colors {{ request()->routeIs($route) ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </header>

            <!-- CONTEÚDO PRINCIPAL DA PÁGINA -->
            <main>
                @if (isset($header))
                    <div class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </div>
                @endif
                <div class="py-6">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>

        <script>
            function markNotificationsAsRead() {
                fetch('{{ route("notifications.markAsRead") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Notificações marcadas como lidas!');
                    } else {
                        console.error('Falha ao marcar as notificações como lidas.');
                    }
                })
                .catch(error => {
                    console.error('Erro na requisição:', error);
                });
            }
        </script>
    </body>
</html>

