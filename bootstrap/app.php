<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Esta linha diz ao Laravel para confiar nos servidores proxy (como os do Forge).
        $middleware->trustProxies(at: '*');

        // --- INÍCIO DA CORREÇÃO ---
        // Esta linha regista o nosso novo "segurança" de administrador
        // com o apelido 'admin', para que o possamos usar nas nossas rotas.
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);
        // --- FIM DA CORREÇÃO ---
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

