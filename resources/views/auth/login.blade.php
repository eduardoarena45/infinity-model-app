<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
        </div>
        <div class="mt-4">
            <x-input-label for="password" value="Senha" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
        </div>
        <div class="block mt-4"><label for="remember_me" class="inline-flex items-center"><input id="remember_me" type="checkbox" class="rounded dark:border-gray-700 text-[--color-primary] shadow-sm" name="remember"></label></div>
        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request')) <a class="underline text-sm hover:text-gray-900" href="{{ route('password.request') }}">Esqueceu a sua senha?</a> @endif
            <x-primary-button class="ms-3" style="background-color: var(--color-primary);">Entrar</x-primary-button>
        </div>
    </form>
</x-guest-layout>