<?php

namespace App\Http\Controllers;

use App\Models\Acompanhante;
use App\Models\Servico;
use App\Models\Cidade;
use App\Models\Estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB; // Adicione esta linha

class VitrineController extends Controller
{
    /**
     * Lista os ESTADOS que têm perfis aprovados para a página inicial.
     */
    public function listarCidades()
    {
        // Cache para a lista de estados por 1 hora (3600 segundos)
        $estados = Cache::remember('lista_estados', 3600, function () {
            return Estado::whereHas('cidades.acompanhantes', function ($query) {
                $query->where('status', 'aprovado');
            })->orderBy('nome')->get();
        });

        return view('cidades', ['estados' => $estados]);
    }

    /**
     * Mostra a vitrine de uma cidade específica.
     */
    public function mostrarPorCidade(Request $request, string $genero, string $cidadeNome)
    {
        // Cria uma chave de cache única para esta combinação de cidade, gênero, filtros e página
        $servicosSelecionadosIds = implode('-', $request->input('servicos', []));
        $paginaAtual = $request->input('page', 1);
        $cacheKey = "vitrine_{$cidadeNome}_{$genero}_servicos_{$servicosSelecionadosIds}_pagina_{$paginaAtual}";

        // Guarda os dados da vitrine no cache por 1 hora
        $dadosVitrine = Cache::remember($cacheKey, 3600, function () use ($request, $genero, $cidadeNome) {
            
            // --- INÍCIO DA NOVA LÓGICA DE ORDENAÇÃO COM PRIORIDADE ---

            // Cria uma "semente" única para a semana atual (ex: "2025-32")
            $seed = date('Y-W');

            $baseQuery = Acompanhante::query()
                ->select('acompanhantes.*') // Seleciona explicitamente as colunas de acompanhantes
                // Junta as tabelas para aceder à prioridade do plano ativo
                ->leftJoin('users', 'acompanhantes.user_id', '=', 'users.id')
                ->leftJoin('assinaturas', function ($join) {
                    $join->on('users.id', '=', 'assinaturas.user_id')
                         ->where('assinaturas.status', '=', 'ativa')
                         ->where('assinaturas.data_fim', '>', now());
                })
                ->leftJoin('planos', 'assinaturas.plano_id', '=', 'planos.id')
                // Filtros principais
                ->where('acompanhantes.genero', $genero)
                ->whereHas('cidade', function ($query) use ($cidadeNome) {
                    $query->where('nome', $cidadeNome);
                })
                ->where('acompanhantes.status', 'aprovado');

            if ($request->filled('servicos')) {
                $servicosSelecionados = $request->servicos;
                // O whereHas é aplicado na query principal
                $baseQuery->whereHas('servicos', function ($q) use ($servicosSelecionados) {
                    $q->whereIn('servicos.id', $servicosSelecionados);
                });
            }

            // Ordena primeiro pela prioridade do plano (menor número = mais alto)
            // Perfis sem plano ativo recebem prioridade 999 (vão para o fim)
            // Depois, aplica o rodízio aleatório semanal
            $destaques = (clone $baseQuery)
                ->where('acompanhantes.is_featured', true)
                ->orderByRaw('IFNULL(planos.prioridade, 999) ASC')
                ->inRandomOrder($seed)
                ->get();
                
            $acompanhantesNormais = (clone $baseQuery)
                ->where('acompanhantes.is_featured', false)
                ->orderByRaw('IFNULL(planos.prioridade, 999) ASC')
                ->inRandomOrder($seed)
                ->paginate(12)
                ->withQueryString();
            
            // --- FIM DA NOVA LÓGICA ---
            
            return ['destaques' => $destaques, 'acompanhantes' => $acompanhantesNormais];
        });

        // O resto dos dados não precisa de cache pesado
        $servicos = Servico::orderBy('nome')->get();

        return view('vitrine', [
            'destaques' => $dadosVitrine['destaques'],
            'acompanhantes' => $dadosVitrine['acompanhantes'],
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

        $acompanhante->profileViews()->create();
        $acompanhante->load('servicos', 'midias');

        $avaliacoes = $acompanhante->avaliacoes()
                                  ->where('status', 'aprovado')
                                  ->latest()
                                  ->paginate(4);

        return view('perfil', [
            'acompanhante' => $acompanhante,
            'avaliacoes' => $avaliacoes,
        ]);
    }

    /**
     * Salva uma nova avaliação para uma acompanhante.
     */
    public function storeAvaliacao(Request $request, Acompanhante $acompanhante)
    {
        $request->validate([
            'nome_avaliador' => 'required|string|max:255',
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|max:1000',
        ]);

        $acompanhante->avaliacoes()->create([
            'nome_avaliador' => $request->nome_avaliador,
            'nota' => $request->nota,
            'comentario' => $request->comentario,
            'status' => 'pendente',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Sua avaliação foi enviada para moderação. Obrigado!');
    }
}
