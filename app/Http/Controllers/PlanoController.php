<?php
namespace App\Http\Controllers;
use App\Models\Plano;
use App\Models\Assinatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanoController extends Controller
{
    // Mostra a página para escolher um plano
    public function selecionar() {
        // Busca todos os planos do banco de dados, ordenados pelo preço
        $planos = Plano::orderBy('preco')->get();
        
        // Busca a assinatura ativa do usuário para saber qual plano destacar
        $assinaturaAtiva = Auth::user()->assinatura()
                                ->where('status', 'ativa')
                                ->where('data_fim', '>', now())
                                ->first();

        // O código de debug foi removido. Agora o programa continua e carrega a view.

        return view('planos.selecionar', [
            'planos' => $planos,
            'assinaturaAtivaId' => $assinaturaAtiva->plano_id ?? null
        ]);
    }

    // Cria a assinatura pendente e redireciona para as instruções de pagamento
    public function assinar(Request $request, Plano $plano) {
        $user = Auth::user();

        if ($plano->preco == 0) {
            return redirect()->route('dashboard')->with('error', 'Não é possível assinar o plano grátis.');
        }

        $assinatura = $user->assinatura()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'plano_id' => $plano->id, 
                'data_inicio' => now(),
                'data_fim' => now()->addDays(30),
                'status' => 'aguardando_pagamento'
            ]
        );
        
        return redirect()->route('planos.pagamento', ['assinatura' => $assinatura->id]);
    }

    // Mostra a página de instruções de pagamento
    public function mostrarPagamento(Assinatura $assinatura) {
        if ($assinatura->user_id !== Auth::id()) {
            abort(403);
        }
        return view('planos.pagamento', ['assinatura' => $assinatura]);
    }
}
