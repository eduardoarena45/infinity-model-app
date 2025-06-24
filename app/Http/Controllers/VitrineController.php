<?php

namespace App\Http\Controllers;

use App\Models\Acompanhante;
use App\Models\Servico;
use Illuminate\Http\Request;

class VitrineController extends Controller
{
    /**
     * MÉTODO SIMPLIFICADO: Busca apenas as cidades para a página inicial.
     */
    public function listarCidades()
    {
        $cidades = Acompanhante::select('cidade')
                                ->whereNotNull('cidade')
                                ->distinct()
                                ->orderBy('cidade', 'asc')
                                ->get();

        return view('cidades', ['cidades' => $cidades]);
    }

    /**
     * MÉTODO ATUALIZADO: Agora lida com a vitrine E com os filtros de serviços.
     */
    public function mostrarPorCidade(Request $request, string $cidade)
    {
        $query = Acompanhante::query()->where('cidade', $cidade);

        // Filtra por serviços, se algum serviço for selecionado no formulário
        if ($request->filled('servicos')) {
            $servicosSelecionados = $request->servicos;
            $query->whereHas('servicos', function ($q) use ($servicosSelecionados) {
                $q->whereIn('servicos.id', $servicosSelecionados);
            });
        }

        $acompanhantes = $query->latest()->get();
        $servicos = Servico::orderBy('nome')->get(); // Pega todos os serviços para o formulário de filtro

        return view('vitrine', [
            'acompanhantes' => $acompanhantes,
            'cidadeNome' => $cidade,
            'servicos' => $servicos,
            'servicosSelecionados' => $request->input('servicos', []), // Para manter os checkboxes marcados
        ]);
    }

    /**
     * Mostra a página de perfil individual (este método não muda).
     */
    public function show(Acompanhante $acompanhante)
    {
        $acompanhante->load('servicos', 'midias'); // Carrega as relações
        return view('perfil', ['acompanhante' => $acompanhante]);
    }
}