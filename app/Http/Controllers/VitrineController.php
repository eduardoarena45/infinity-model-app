<?php

namespace App\Http\Controllers;

use App\Models\Acompanhante;
use App\Models\Servico;
use App\Models\Cidade;
use App\Models\Estado;
use Illuminate\Http\Request;

class VitrineController extends Controller
{
    /**
     * Lista os ESTADOS que têm perfis aprovados para a página inicial.
     */
    public function listarCidades()
    {
        $estados = Estado::whereHas('cidades.acompanhantes', function ($query) {
            $query->where('status', 'aprovado');
        })->orderBy('nome')->get();

        return view('cidades', ['estados' => $estados]);
    }

    /**
     * Mostra a vitrine de uma cidade específica.
     */
    public function mostrarPorCidade(Request $request, string $genero, string $cidadeNome)
    {
        $baseQuery = Acompanhante::query()
            ->where('genero', $genero)
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

        $destaques = (clone $baseQuery)->where('is_featured', true)->latest()->get();
        $acompanhantesNormais = (clone $baseQuery)->where('is_featured', false)->latest()->paginate(12)->withQueryString();
        
        $servicos = Servico::orderBy('nome')->get();

        return view('vitrine', [
            'destaques' => $destaques,
            'acompanhantes' => $acompanhantesNormais,
            'cidadeNome' => $cidadeNome,
            'servicos' => $servicos,
            'servicosSelecionados' => $request->input('servicos', []),
            'genero' => $genero,
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

        // Adiciona esta linha para registar a visualização do perfil
        $acompanhante->profileViews()->create();

        // Carrega as outras relações normalmente
        $acompanhante->load('servicos', 'midias');

        // Busca as avaliações APROVADAS de forma paginada (4 por página)
        $avaliacoes = $acompanhante->avaliacoes()
                                  ->where('status', 'aprovado')
                                  ->latest() // Ordena pelas mais recentes
                                  ->paginate(4);

        return view('perfil', [
            'acompanhante' => $acompanhante,
            'avaliacoes' => $avaliacoes, // Passa a coleção paginada para a view
        ]);
    }

    /**
     * Salva uma nova avaliação para uma acompanhante.
     */
    public function storeAvaliacao(Request $request, Acompanhante $acompanhante)
    {
        // 1. Valida todos os campos do formulário
        $request->validate([
            'nome_avaliador' => 'required|string|max:255',
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|max:1000',
        ]);

        // 2. Cria a avaliação com todos os dados corretos para moderação
        $acompanhante->avaliacoes()->create([
            'nome_avaliador' => $request->nome_avaliador,
            'nota' => $request->nota,
            'comentario' => $request->comentario,
            'status' => 'pendente', // <-- Salva como pendente
            'ip_address' => $request->ip(), // <-- Salva o IP
        ]);

        return back()->with('success', 'Sua avaliação foi enviada para moderação. Obrigado!');
    }
}
