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
        $query = Acompanhante::query()->where('cidade', $cidade);

        if ($request->filled('servicos')) {
            $servicosSelecionados = $request->servicos;
            $query->whereHas('servicos', function ($q) use ($servicosSelecionados) {
                $q->whereIn('servicos.id', $servicosSelecionados);
            });
        }

        // ALTERAÇÃO PRINCIPAL: Trocamos .get() por .paginate(12)
        $acompanhantes = $query->latest()->paginate(12)->withQueryString();

        $servicos = Servico::orderBy('nome')->get();

        return view('vitrine', [
            'acompanhantes' => $acompanhantes,
            'cidadeNome' => $cidade,
            'servicos' => $servicos,
            'servicosSelecionados' => $request->input('servicos', []),
        ]);
    }

    public function show(Acompanhante $acompanhante)
    {
        $acompanhante->load('servicos', 'midias');
        return view('perfil', ['acompanhante' => $acompanhante]);
    }
}
