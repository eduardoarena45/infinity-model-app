<?php

namespace App\Http\Controllers;

use App\Models\Acompanhante;
use App\Models\Servico;
use App\Models\Cidade;
use App\Models\Estado; // Importa o Model Estado
use Illuminate\Http\Request;

class VitrineController extends Controller
{
    /**
     * Lista os ESTADOS que têm perfis aprovados para a página inicial.
     */
    public function listarCidades()
    {
        // LÓGICA ATUALIZADA: Busca Estados que têm cidades com acompanhantes aprovadas.
        // Isso garante que só aparecerão estados com perfis ativos.
        $estados = Estado::whereHas('cidades.acompanhantes', function ($query) {
            $query->where('status', 'aprovado');
        })->orderBy('nome')->get();

        // A view 'cidades' agora receberá a lista de estados.
        return view('cidades', ['estados' => $estados]);
    }

    /**
     * Mostra a vitrine de uma cidade específica.
     */
    public function mostrarPorCidade(Request $request, string $cidadeNome)
    {
        // LÓGICA CORRIGIDA: A query agora busca pela relação com a cidade
        $baseQuery = Acompanhante::query()
            ->whereHas('cidade', function ($query) use ($cidadeNome) {
                $query->where('nome', $cidadeNome);
            })
            ->where('status', 'aprovado');

        if ($request->filled('servicos')) {
            $servicosSelecionados = $request->servicos;
            $baseQuery->whereHas('servicos', function ($q) use ($servicosSelecionados) {
                $q->whereIn('servicos.id', $servicosSelecionados);
            });
        }

        // Ordena para que os perfis em destaque apareçam primeiro
        $destaques = (clone $baseQuery)->where('is_featured', true)->latest()->get();
        $acompanhantesNormais = (clone $baseQuery)->where('is_featured', false)->latest()->paginate(12)->withQueryString();
        
        $servicos = Servico::orderBy('nome')->get();

        return view('vitrine', [
            'destaques' => $destaques,
            'acompanhantes' => $acompanhantesNormais,
            'cidadeNome' => $cidadeNome,
            'servicos' => $servicos,
            'servicosSelecionados' => $request->input('servicos', []),
        ]);
    }

    /**
     * Mostra o perfil detalhado de uma acompanhante.
     */
    public function show(Acompanhante $acompanhante)
    {
        if ($acompanhante->status !== 'aprovado') {
            abort(404);
        }
        $acompanhante->load('servicos', 'midias', 'avaliacoes.user');
        return view('perfil', ['acompanhante' => $acompanhante]);
    }

    /**
     * Salva uma nova avaliação para uma acompanhante.
     */
    public function storeAvaliacao(Request $request, Acompanhante $acompanhante)
    {
        $request->validate([
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|max:1000',
        ]);

        $acompanhante->avaliacoes()->create([
            'user_id' => auth()->id(), // Garante que apenas usuários logados avaliem
            'nota' => $request->nota,
            'comentario' => $request->comentario,
        ]);

        return back()->with('success', 'Sua avaliação foi enviada com sucesso!');
    }
}