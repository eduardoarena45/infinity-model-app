<?php
namespace App\Http\Controllers;
use App\Models\Plano;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanoController extends Controller
{
    // Mostra a página para escolher um plano
    public function selecionar() {
        // Busca todos os planos do banco de dados, ordenados pelo preço
        $planos = Plano::orderBy('preco')->get();
        
        return view('planos.selecionar', ['planos' => $planos]);
    }

    // Assina o plano escolhido
    public function assinar(Request $request, Plano $plano) {
        $user = Auth::user();
        // Lógica de pagamento viria aqui
        // Por agora, apenas criamos/atualizamos a assinatura
        $user->assinatura()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'plano_id' => $plano->id, 
                'data_inicio' => now(),
                'data_fim' => now()->addDays(30),
                'status' => 'ativa'
            ]
        );
        return redirect()->route('dashboard')->with('status', 'plano-assinado');
    }
}
