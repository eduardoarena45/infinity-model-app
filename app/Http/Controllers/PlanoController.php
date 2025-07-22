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
        $planos = Plano::orderBy('preco')->get();
        
        // --- CORREÇÃO AQUI ---
        // Agora usamos a relação 'assinaturaAtiva' que já criamos no Model User.
        // Acessamos como uma propriedade, não como um método, pois a lógica já está na relação.
        $assinaturaAtiva = Auth::user()->assinaturaAtiva;

        return view('planos.selecionar', [
            'planos' => $planos,
            // Usamos o operador 'nullsafe' (?) para evitar erros se a assinatura não existir
            'assinaturaAtivaId' => $assinaturaAtiva?->plano_id
        ]);
    }

    // Cria a assinatura pendente e redireciona para as instruções de pagamento
    public function assinar(Request $request, Plano $plano) {
        $user = Auth::user();

        if ($plano->preco == 0) {
            return redirect()->route('dashboard')->with('error', 'Não é possível assinar o plano grátis.');
        }

        // --- CORREÇÃO AQUI ---
        // Usamos o Model Assinatura diretamente. Isso é mais robusto e não depende do nome da relação no User.
        $assinatura = Assinatura::updateOrCreate(
            ['user_id' => $user->id], // Condição para encontrar a assinatura (pelo user_id)
            [ // Dados para atualizar ou criar uma nova
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