<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
// Adicionamos o import para a classe Response
use Illuminate\Http\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    // --- INÍCIO DA CORREÇÃO DEFINITIVA ---
    // A função agora retorna uma 'Response' em vez de uma 'RedirectResponse'
    public function destroy(Request $request): Response
    {
        // 1. A lógica de logout no servidor continua a mesma, está perfeita.
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 2. Em vez de um simples redirect, criamos uma resposta HTML completa.
        // Esta resposta contém o comando JavaScript que força o navegador a obedecer.
        return response(
            <<<HTML
            <!DOCTYPE html>
            <html>
                <head>
                    <meta charset="utf-8">
                    <title>A terminar a sessão...</title>
                    <script>
                        // 'window.location.replace' substitui a página atual no histórico do navegador.
                        // Isto impede que o botão "Voltar" consiga aceder à página anterior.
                        window.location.replace('/');
                    </script>
                </head>
                <body>
                    A terminar a sessão... Se não for redirecionado, <a href="/">clique aqui</a>.
                </body>
            </html>
            HTML
        )->withHeaders([
            // Reafirmamos os cabeçalhos anti-cache para garantir
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Sat, 01 Jan 2000 00:00:00 GMT',
        ]);
    }
    // --- FIM DA CORREÇÃO DEFINITIVA ---
}
