<?php
namespace App\Http\Controllers;
use App\Models\Plano;
use App\Models\Assinatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanoController extends Controller
{
    public function selecionar() {
        $planos = Plano::orderBy('preco')->get();
        $assinaturaAtiva = Auth::user()->assinaturaAtiva;
        return view('planos.selecionar', [
            'planos' => $planos,
            'assinaturaAtivaId' => $assinaturaAtiva?->plano_id
        ]);
    }

    public function assinar(Request $request, Plano $plano) {
        $user = Auth::user();

        if ($plano->preco == 0) {
            Assinatura::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'plano_id' => $plano->id,
                    'data_inicio' => now(),
                    'data_fim' => null, // Planos grátis são vitalícios
                    'status' => 'ativa',
                    // =======================================================
                    // =================== INÍCIO DA ALTERAÇÃO ==================
                    // Para planos grátis, definimos o valor pago como 0.
                    'valor_pago' => 0.00
                    // ==================== FIM DA ALTERAÇÃO =====================
                ]
            );
            return redirect()->route('profile.edit')->with('status', 'plano-ativado');
        }
        else {
            $assinatura = Assinatura::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'plano_id' => $plano->id,
                    // =======================================================
                    // =================== INÍCIO DA ALTERAÇÃO ==================
                    // Esta é a alteração mais importante: "congelamos" o preço do plano
                    // na nossa nova coluna no momento exato da transação.
                    'valor_pago' => $plano->preco,
                    // ==================== FIM DA ALTERAÇÃO =====================
                    'data_inicio' => now(),
                    // Para planos pagos, a data_fim será ajustada pelo admin após o pagamento.
                    'data_fim' => now()->addDays(30),
                    'status' => 'aguardando_pagamento'
                ]
            );
            return redirect()->route('planos.pagamento', ['assinatura' => $assinatura->id]);
        }
    }

    public function mostrarPagamento(Assinatura $assinatura) {
        if ($assinatura->user_id !== Auth::id()) {
            abort(403);
        }
        return view('planos.pagamento', ['assinatura' => $assinatura]);
    }
}
