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

        // =======================================================
        // =================== INÍCIO DA ALTERAÇÃO ==================
        // =======================================================

        // PASSO 1: O utilizador tem uma assinatura ativa?
        if (!$user->assinaturaAtiva) {
            // Se não tiver, ele só pode aceder às rotas de seleção/pagamento de planos.
            // Se tentar aceder a qualquer outra página, é forçado a escolher um plano.
            $allowedRoutes = ['planos.selecionar', 'planos.assinar', 'planos.pagamento', 'logout'];
            if (!$request->routeIs($allowedRoutes)) {
                return redirect()->route('planos.selecionar');
            }
            // Se estiver numa rota permitida, pode continuar.
            return $next($request);
        }

        // Se chegou aqui, o utilizador JÁ TEM uma assinatura ativa.

        $acompanhante = $user->acompanhante;

        // PASSO 2: O perfil está preenchido com os dados mínimos?
        // Usamos `nome_artistico` como o principal indicador de que o perfil foi preenchido.
        $isProfileIncomplete = !$acompanhante || !$acompanhante->nome_artistico;

        if ($isProfileIncomplete) {
            // Se o perfil está incompleto, a única página que ele pode aceder é a de edição de perfil.
            // Se tentar aceder a qualquer outra (como o dashboard), é forçado a editar o perfil primeiro.
            if (!$request->routeIs('profile.edit')) {
                return redirect()->route('profile.edit');
            }
        }

        // Se chegou aqui, o utilizador tem uma assinatura E um perfil preenchido.
        // O onboarding está concluído e ele pode aceder a qualquer página. Deixe-o passar.
        return $next($request);

        // =======================================================
        // ==================== FIM DA ALTERAÇÃO =====================
        // =======================================================
    }
}
