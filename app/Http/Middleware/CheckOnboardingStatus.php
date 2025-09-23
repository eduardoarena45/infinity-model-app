<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOnboardingStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Se o usuário tem uma assinatura ativa, ele pode aceder a qualquer página. Deixe-o passar.
        if ($user->assinaturaAtiva) {
            return $next($request);
        }

        // Se o usuário NÃO tem uma assinatura ativa, ele só pode aceder às páginas do "funil".
        // Estas são as rotas permitidas durante o onboarding.
        $allowedRoutes = [
            'planos.selecionar',
            'planos.assinar',
            'planos.pagamento',
            'logout',
        ];

        // Se ele está a tentar aceder a uma página que NÃO está na lista permitida...
        if (! $request->routeIs($allowedRoutes)) {
            // ...forçamo-lo a voltar para o início do funil.
            return redirect()->route('planos.selecionar');
        }

        // Se ele está numa página permitida, deixe-o passar.
        return $next($request);
    }
}
