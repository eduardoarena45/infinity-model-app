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
        // --- INÍCIO DA CORREÇÃO FINAL ---
        // Esta linha diz ao Laravel para confiar nos servidores proxy (como os do Forge).
        // Isto é essencial para que as sessões e a segurança (CSRF) funcionem corretamente em produção com HTTPS.
        $middleware->trustProxies(at: '*');
        // --- FIM DA CORREÇÃO FINAL ---
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
