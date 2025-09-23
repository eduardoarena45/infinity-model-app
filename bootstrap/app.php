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

        // Adicionamos os nossos "seguranças" personalizados à lista de apelidos.
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'nocache' => \App\Http\Middleware\PreventBrowserCaching::class,

            // =======================================================================
            // ================ ESTA É A NOVA LINHA ADICIONADA =======================
            // Registamos o guardião que verifica se o onboarding foi concluído.
            'onboarding.check' => \App\Http\Middleware\CheckOnboardingStatus::class,
            // =======================================================================
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

