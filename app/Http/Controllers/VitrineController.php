<?php

namespace App\Http\Controllers;

use App\Models\Acompanhante;
use App\Models\Servico;
use App\Models\Cidade;
use App\Models\Estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VitrineController extends Controller
{
    /**
     * Lista TODOS os estados cadastrados para a página inicial.
     */
    public function listarCidades()
    {
        $estados = Cache::remember('todos_os_estados', 3600, function () {
            return Estado::orderBy('nome')->get();
        });

        return view('cidades', ['estados' => $estados]);
    }

    /**
     * Mostra a vitrine de uma cidade específica.
     */
    public function mostrarPorCidade(Request $request, string $genero, string $cidadeNome)
    {
        $servicosSelecionadosIds = implode('-', $request->input('servicos', []));
        $paginaAtual = $request->input('page', 1);
        $cacheKey = "vitrine_{$cidadeNome}_{$genero}_servicos_{$servicosSelecionadosIds}_pagina_{$paginaAtual}";

        $dadosVitrine = Cache::remember($cacheKey, 3600, function () use ($request, $genero, $cidadeNome) {

            $seed = date('Y-W');

            $baseQuery = Acompanhante::query()
                ->select('acompanhantes.*')
                ->leftJoin('users', 'acompanhantes.user_id', '=', 'users.id')
                ->leftJoin('assinaturas', function ($join) {
                    $join->on('users.id', '=', 'assinaturas.user_id')
                         ->where('assinaturas.status', '=', 'ativa')
                         ->where(function ($query) {
                            $query->where('assinaturas.data_fim', '>', now())
                                  ->orWhereNull('assinaturas.data_fim');
                         });
                })
                ->leftJoin('planos', 'assinaturas.plano_id', '=', 'planos.id')
                // Filtros principais
                ->where('acompanhantes.genero', $genero)
                ->whereHas('cidade', function ($query) use ($cidadeNome) {
                    $query->where('nome', $cidadeNome);
                })
                ->where('acompanhantes.status', 'aprovado');

            // =======================================================
            // === INÍCIO DA ADIÇÃO DA REGRA DE NEGÓCIO (PERFIL COMPLETO) ===
            // =======================================================

            // Garante que só aparecem perfis que têm todos os campos obrigatórios preenchidos
            $baseQuery->whereNotNull('acompanhantes.nome_artistico')
                      ->whereNotNull('acompanhantes.foto_principal_path')
                      ->whereNotNull('acompanhantes.cidade_id')
                      ->whereNotNull('acompanhantes.descricao')
                      ->whereNotNull('acompanhantes.whatsapp')
                      ->whereNotNull('acompanhantes.valor_hora');

            // A regra final e mais importante: o perfil DEVE ter pelo menos uma foto na galeria.
            $baseQuery->whereHas('midias', function ($query) {
                $query->where('type', 'image');
            });

            // =======================================================
            // ==== FIM DA ADIÇÃO DA REGRA DE NEGÓCIO (PERFIL COMPLETO) ====
            // =======================================================


            if ($request->filled('servicos')) {
                $servicosSelecionados = $request->servicos;
                $baseQuery->whereHas('servicos', function ($q) use ($servicosSelecionados) {
                    $q->whereIn('servicos.id', $servicosSelecionados);
                });
            }

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

            return ['destaques' => $destaques, 'acompanhantes' => $acompanhantesNormais];
        });

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
        // A lógica do método show() não precisa de ser alterada, mas podemos adicionar
        // uma verificação extra para garantir que o perfil está completo.
        if ($acompanhante->status !== 'aprovado' || !$acompanhante->isPubliclyReady()) {
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
