<?php

namespace App\Http\Controllers;

use App\Models\Acompanhante;
use App\Models\Servico;
use Illuminate\Http\Request;

class VitrineController extends Controller
{
    public function listarCidades()
    {
        // Agora só conta cidades que têm perfis APROVADOS
        $cidades = Acompanhante::where('status', 'aprovado')
                                ->select('cidade')
                                ->whereNotNull('cidade')
                                ->distinct()
                                ->orderBy('cidade', 'asc')
                                ->get();
        return view('cidades', ['cidades' => $cidades]);
    }

    public function mostrarPorCidade(Request $request, string $cidade)
    {
        // A query base agora já inclui o filtro por status aprovado
        $baseQuery = Acompanhante::query()
                                ->where('cidade', $cidade)
                                ->where('status', 'aprovado');

        if ($request->filled('servicos')) {
            $servicosSelecionados = $request->servicos;
            $baseQuery->whereHas('servicos', function ($q) use ($servicosSelecionados) {
                $q->whereIn('servicos.id', $servicosSelecionados);
            });
        }

        // A lógica de destaques e normais continua, mas já sobre os perfis aprovados
        $destaques = (clone $baseQuery)->where('is_featured', true)->latest()->get();
        $acompanhantesNormais = (clone $baseQuery)->where('is_featured', false)->latest()->paginate(12)->withQueryString();
        $servicos = Servico::orderBy('nome')->get();

        return view('vitrine', [
            'destaques' => $destaques,
            'acompanhantes' => $acompanhantesNormais,
            'cidadeNome' => $cidade,
            'servicos' => $servicos,
            'servicosSelecionados' => $request->input('servicos', []),
        ]);
    }

    public function show(Acompanhante $acompanhante)
    {
        // Garante que ninguém possa aceder a um perfil não aprovado pela URL
        if ($acompanhante->status !== 'aprovado') {
            abort(404); // Mostra uma página de "Não Encontrado"
        }
        $acompanhante->load('servicos', 'midias', 'avaliacoes');
        return view('perfil', ['acompanhante' => $acompanhante]);
    }

    // ... (resto dos métodos: storeAvaliacao)
}