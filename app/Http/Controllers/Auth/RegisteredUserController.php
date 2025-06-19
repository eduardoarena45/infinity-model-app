<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Acompanhante; // Importa o nosso Model de Acompanhante
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Mostra a tela de registro.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Lida com uma nova requisição de registro.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validação dos dados que chegam do formulário de registro
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Cria o novo usuário no banco de dados
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // LINHA ADICIONADA:
        // Assim que o usuário é criado, criamos um perfil de acompanhante vazio
        // e o vinculamos automaticamente ao novo usuário.
        $user->acompanhante()->create();

        // Dispara um evento para, por exemplo, enviar um e-mail de boas-vindas
        event(new Registered($user));

        // Faz o login automático do usuário recém-criado
        Auth::login($user);

        // Redireciona o usuário para o painel de controle (dashboard)
        return redirect(route('dashboard', absolute: false));
    }
}
