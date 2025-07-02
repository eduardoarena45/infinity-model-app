<?php

namespace App\Http\Controllers;

use App\Models\Acompanhante;
use App\Models\Servico;
use Illuminate\Http\Request;

class VitrineController extends Controller
{
    /**
     * MÉTODO ATUALIZADO: Agora busca cidades E todos os serviços disponíveis.
     */
    public function listarCidades()
    {
        $cidades = Acompanhante::select('cidade')->whereNotNull('cidade')->distinct()->orderBy('cidade', 'asc')->get();
        $servicos = Servico::orderBy('nome')->get(); // Busca todos os serviços

        return view('cidades', [
            'cidades' => $cidades,
            'servicos' => $servicos, // Envia os serviços para a view
        ]);
    }

    /**
     * MÉTODO ATUALIZADO: Continua a mostrar os perfis de uma cidade específica.
     */
    public function mostrarPorCidade(string $cidade)
    {
        $acompanhantes = Acompanhante::where('cidade', $cidade)->latest()->paginate(12)->withQueryString();
        return view('vitrine', [
            'acompanhantes' => $acompanhantes,
            'cidadeNome' => $cidade,
            'titulo' => "Acompanhantes em {$cidade}" // Título para a página
        ]);
    }

    /**
     * NOVO MÉTODO: Lida com a busca do formulário.
     */
    public function search(Request $request)
    {
        $query = Acompanhante::query();

        // Filtra por cidade, se uma cidade for selecionada
        if ($request->filled('cidade')) {
            $query->where('cidade', $request->cidade);
        }

        // Filtra por serviços, se algum serviço for selecionado
        if ($request->filled('servicos')) {
            $servicosSelecionados = $request->servicos;
            $query->whereHas('servicos', function ($q) use ($servicosSelecionados) {
                $q->whereIn('servicos.id', $servicosSelecionados);
            });
        }

        $acompanhantes = $query->latest()->paginate(12)->withQueryString();

        return view('vitrine', [
            'acompanhantes' => $acompanhantes,
            'cidadeNome' => $request->cidade ?? 'Brasil', // Cidade para o título
            'titulo' => "Resultados da sua busca" // Título para a página
        ]);
    }

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