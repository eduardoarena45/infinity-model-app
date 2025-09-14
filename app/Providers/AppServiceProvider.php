<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Acompanhante; // Adicione esta linha
use App\Observers\AcompanhanteObserver; // Adicione esta linha

// --- INÍCIO DA CORREÇÃO ---
// Adicionamos as duas linhas abaixo para criar as nossas "leis" de autorização
use Illuminate\Support\Facades\Gate;
use App\Models\User;
// --- FIM DA CORREÇÃO ---

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

        // Regista o nosso novo observador para limpar o cache automaticamente
        Acompanhante::observe(AcompanhanteObserver::class);

        // --- INÍCIO DA CORREÇÃO DEFINITIVA ---
        // Esta "lei" (Gate) define a regra de quem pode aceder ao painel de administração.
        // Ela diz: "Permita o acesso se a propriedade 'is_admin' do utilizador for verdadeira."
        Gate::define('viewAdminPanel', function (User $user) {
            return $user->is_admin;
        });
        // --- FIM DA CORREÇÃO DEFINITIVA ---
    }
}
