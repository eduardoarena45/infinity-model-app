<x-guest-layout>
    {{-- A estrutura agora é idêntica à da página de cadastro, sem o "quadro" extra --}}
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="dark:text-gray-300" />
            <x-text-input id="email" class="block mt-1 w-full dark:bg-gray-900 dark:border-gray-600" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Senha')" class="dark:text-gray-300" />
            <x-text-input id="password" class="block mt-1 w-full dark:bg-gray-900 dark:border-gray-600"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="flex items-center">
                {{-- CÓDIGO CORRIGIDO: Usando HTML puro para o checkbox --}}
                <input id="remember_me" type="checkbox" name="remember" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                <span class="ms-2 text-sm text-gray-400">{{ __('Lembrar-me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-6">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-cyan-400 hover:text-cyan-200 rounded-md" href="{{ route('password.request') }}">
                    {{ __('Esqueceu a sua senha?') }}
                </a>
            @endif

            <button type="submit" class="ms-4 inline-flex items-center px-4 py-2 bg-cyan-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition ease-in-out duration-150">
                {{ __('Entrar') }}
            </button>
        </div>
        
        <div class="text-center mt-4">
            <a class="underline text-sm text-gray-400 hover:text-gray-100 rounded-md" href="{{ route('register') }}">
                {{ __('Não tem uma conta? Registre-se') }}
            </a>
        </div>
    </form>
</x-guest-layout>
