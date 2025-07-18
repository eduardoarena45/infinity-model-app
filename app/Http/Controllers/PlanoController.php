<?php
namespace App\Http\Controllers;
use App\Models\Plano;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanoController extends Controller
{
    // Mostra a página para escolher um plano
    public function selecionar() {
        $planos = Plano::orderBy('preco')->get();
        // Adiciona um plano "Grátis" manualmente para exibição, se não existir no banco
        $planoGratis = (object)[
            'nome' => 'Plano Grátis',
            'preco' => 0.00,
            'descricao' => 'O ponto de partida confiável com selo de verificação e WhatsApp visível.',
            'limite_fotos' => 4,
            'permite_videos' => false,
            'destaque' => false,
        ];
        return view('planos.selecionar', ['planos' => $planos, 'planoGratis' => $planoGratis]);
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
