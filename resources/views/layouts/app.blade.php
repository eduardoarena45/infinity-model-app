<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }} - Painel</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                    <h4 class="mx-2 mt-2 font-medium text-gray-200">{{ Auth::user()->name }}</h4>
                </div>
                <nav class="px-2 space-y-1">
                    @php
                        $navLinks = [
                            'dashboard' => 'Painel',
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
                    <div class="relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>
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
                </header>
                <main class="flex-1 p-6 overflow-y-auto">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>