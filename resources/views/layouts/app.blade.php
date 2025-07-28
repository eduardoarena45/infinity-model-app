<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }} - Painel</title>

        <!-- Bloco de Favicons Completo -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
        <link rel="shortcut icon" href="{{ asset('favicon/favicon.ico') }}">
        <!-- Fim do Bloco de Favicons -->

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <style>
            :root {
                --color-primary: #4E2A51; 
                --color-accent: #B76E79;  
                --color-neutral: #36454F; 
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100 dark:bg-gray-900">
            <aside class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-800 text-white transform transition-transform duration-300 ease-in-out sm:translate-x-0" :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
                <div class="flex items-center justify-center h-20 border-b border-gray-700">
                    <a href="{{ route('cidades.index') }}" class="text-2xl font-bold text-white">
                        Infinity Model
                    </a>
                </div>
                <div class="flex flex-col items-center mt-6 mb-4">
                    <form id="avatar-form" action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="avatar-upload-input" class="cursor-pointer">
                            <img id="avatar-preview" class="object-cover w-24 h-24 rounded-full border-2 border-blue-500" 
                                 src="{{ Auth::user()->private_avatar_url }}" 
                                 alt="Avatar Privado">
                        </label>
                        <input type="file" id="avatar-upload-input" name="avatar" class="hidden" accept="image/*" onchange="document.getElementById('avatar-form').submit()">
                    </form>
                </div>
                <nav class="px-2 space-y-1">
                    @php
                        $navLinks = [
                            'dashboard' => 'Desempenho',
                            'profile.edit' => 'Editar Perfil',
                            'galeria.gerir' => 'Gerir Galeria',
                            'planos.selecionar' => 'Meu Plano',
                        ];
                    @endphp
                    @foreach ($navLinks as $route => $label)
                        <a href="{{ route($route) }}" 
                           class="flex items-center px-4 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs($route) ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </nav>
            </aside>
            <div class="flex-1 flex flex-col sm:ml-64">
                <header class="flex justify-between items-center p-4 bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 dark:text-gray-400 focus:outline-none sm:hidden">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <div class="flex-1">
                        @if (isset($header))
                            {{ $header }}
                        @endif
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        {{-- Bloco de Notificações Atualizado --}}
                        <div x-data="{ open: false, unreadCount: {{ $unreadNotifications->count() }} }" class="relative">
                            <button @click="open = !open; if(open && unreadCount > 0) { markNotificationsAsRead(); unreadCount = 0; }" class="relative p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                                <template x-if="unreadCount > 0">
                                    <span class="absolute top-0 right-0 h-4 w-4 flex items-center justify-center rounded-full bg-red-500 text-white text-xs" x-text="unreadCount"></span>
                                </template>
                            </button>

                            <div x-show="open" @click.away="open = false" 
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-md shadow-lg overflow-hidden z-20"
                                style="display: none;">
                                
                                <div class="py-2 px-4 border-b dark:border-gray-700">
                                    <h3 class="font-bold text-gray-900 dark:text-white">Notificações</h3>
                                </div>
                                <div class="divide-y divide-gray-100 dark:divide-gray-700 max-h-96 overflow-y-auto">
                                    @if(isset($unreadNotifications))
                                        @forelse($unreadNotifications as $notification)
                                            @php
                                                $borderColorClass = match ($notification->data['type'] ?? 'default') {
                                                    'success' => 'border-green-500',
                                                    'info'    => 'border-blue-500',
                                                    default   => 'border-transparent',
                                                };
                                            @endphp
                                            <a href="{{ $notification->data['url'] ?? '#' }}" class="block px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 border-l-4 {{ $borderColorClass }} transition-colors">
                                                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $notification->data['title'] }}</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $notification->data['message'] }}</p>
                                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                            </a>
                                        @empty
                                            <div class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                                Nenhuma notificação nova.
                                            </div>
                                        @endforelse
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ Auth::user()->email }}</div>
                                        <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
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
                    </div>
                </header>
                <main class="flex-1 p-6 overflow-y-auto">
                    {{ $slot }}
                </main>
            </div>
        </div>

        {{-- NOVO SCRIPT PARA MARCAR NOTIFICAÇÕES COMO LIDAS --}}
        <script>
            function markNotificationsAsRead() {
                fetch('{{ route("notifications.markAsRead") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                }).catch(error => {
                    console.error('Erro ao marcar notificações como lidas:', error);
                });
            }
        </script>
    </body>
</html>
