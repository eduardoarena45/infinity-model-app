<?php

namespace App\Http\Controllers;

use App\Models\Acompanhante;
use App\Models\Servico;
use Illuminate\Http\Request;

class VitrineController extends Controller
{
    public function listarCidades()
    {
        $cidades = Acompanhante::select('cidade')->whereNotNull('cidade')->distinct()->orderBy('cidade', 'asc')->get();
        return view('cidades', ['cidades' => $cidades]);
    }

    public function mostrarPorCidade(Request $request, string $cidade)
    {
        $baseQuery = Acompanhante::query()->where('cidade', $cidade);

        if ($request->filled('servicos')) {
            $servicosSelecionados = $request->servicos;
            $baseQuery->whereHas('servicos', function ($q) use ($servicosSelecionados) {
                $q->whereIn('servicos.id', $servicosSelecionados);
            });
        }

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

    /**
     * MÉTODO CORRIGIDO: Agora carrega todas as relações necessárias
     * para a página de perfil, evitando a página em branco.
     */
    public function show(Acompanhante $acompanhante)
    {
        $acompanhante->load('servicos', 'midias', 'avaliacoes');
        return view('perfil', ['acompanhante' => $acompanhante]);
    }

    public function storeAvaliacao(Request $request, Acompanhante $acompanhante)
    {
        $validated = $request->validate([
            'nome_autor' => 'required|string|max:255',
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:1000',
        ]);
        $acompanhante->avaliacoes()->create($validated);
        return redirect()->route('vitrine.show', $acompanhante)->with('success', 'Avaliação enviada com sucesso!');
    }
}