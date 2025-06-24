<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str; // Importa a classe Str para manipulação de strings

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        // Validação ATUALIZADA: removemos o campo 'name'
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Criação do utilizador ATUALIZADA:
        // Geramos um nome padrão a partir do e-mail.
        $user = User::create([
            'name' => Str::before($request->email, '@'),
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Cria o perfil de acompanhante vazio
        $user->acompanhante()->create();

        event(new Registered($user));

        Auth::login($user);

        // Redirecionamento ATUALIZADO: agora vai para a página de edição do perfil
        return redirect(route('profile.edit'));
    }
}
