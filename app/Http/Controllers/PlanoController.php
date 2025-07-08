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
        return view('planos.selecionar', ['planos' => $planos]);
    }
    // Assina o plano escolhido
    public function assinar(Request $request, Plano $plano) {
        $user = Auth::user();
        // Cria ou atualiza a assinatura do utilizador
        $user->assinatura()->updateOrCreate(
            ['user_id' => $user->id],
            ['plano_id' => $plano->id, 'data_expiracao' => now()->addDays(30)]
        );
        // Redireciona para a edição do perfil pela primeira vez
        return redirect()->route('profile.edit')->with('status', 'plano-assinado');
    }
}