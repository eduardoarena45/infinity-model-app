<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Adicione esta linha
use Illuminate\Support\Facades\Auth; // Adicione esta linha

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Partilha as notificações com as views do painel da acompanhante
        View::composer('layouts.app', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                // Carrega as notificações não lidas para o utilizador autenticado
                $unreadNotifications = $user->unreadNotifications;
                // Partilha a variável com a view
                $view->with('unreadNotifications', $unreadNotifications);
            } else {
                // Se não houver utilizador, partilha uma coleção vazia para evitar erros
                $view->with('unreadNotifications', collect());
            }
        });
    }
}
