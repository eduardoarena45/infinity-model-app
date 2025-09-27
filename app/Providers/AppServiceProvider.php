<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Acompanhante;
use App\Observers\AcompanhanteObserver;

// --- INÍCIO DA CORREÇÃO ---
use Illuminate\Support\Facades\Gate;
use App\Models\User;
// --- FIM DA CORREÇÃO ---

// =======================================================
// =================== INÍCIO DA ALTERAÇÃO ==================
// Importamos o nosso novo "vigia" e o modelo que ele vai vigiar.
use App\Models\Assinatura;
use App\Observers\AssinaturaObserver;
// =======================================================
// ==================== FIM DA ALTERAÇÃO =====================
// =======================================================

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
                $unreadNotifications = $user->unreadNotifications;
                $view->with('unreadNotifications', $unreadNotifications);
            } else {
                $view->with('unreadNotifications', collect());
            }
        });

        // Regista o observador para limpar o cache automaticamente
        Acompanhante::observe(AcompanhanteObserver::class);

        // =======================================================
        // =================== INÍCIO DA ALTERAÇÃO ==================
        // Esta é a linha que "liga" o nosso novo vigia.
        // Ela diz ao Laravel: "A partir de agora, use o AssinaturaObserver para vigiar o modelo Assinatura."
        Assinatura::observe(AssinaturaObserver::class);
        // =======================================================
        // ==================== FIM DA ALTERAÇÃO =====================
        // =======================================================

        // --- INÍCIO DA CORREÇÃO DEFINITIVA ---
        Gate::define('viewAdminPanel', function (User $user) {
            return $user->is_admin;
        });
        // --- FIM DA CORREÇÃO DEFINITIVA ---
    }
}
