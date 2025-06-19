<?php

namespace App\Http\Controllers;

use App\Models\Acompanhante;
use Illuminate\Http\Request;

class VitrineController extends Controller
{
    /**
     * NOVO MÉTODO: Pega todas as cidades distintas que têm perfis
     * e as envia para uma nova página de seleção.
     */
    public function listarCidades()
    {
        // Busca no banco de dados, pega apenas a coluna 'cidade',
        // remove as duplicadas (distinct) e ordena.
        $cidades = Acompanhante::select('cidade')
                                ->whereNotNull('cidade') // Ignora perfis sem cidade preenchida
                                ->distinct()
                                ->orderBy('cidade', 'asc')
                                ->get();

        // Retorna a nova view que vamos criar
        return view('cidades', ['cidades' => $cidades]);
    }

    /**
     * NOVO MÉTODO: Recebe um nome de cidade da URL, filtra os perfis
     * e os envia para a view da vitrine.
     */
    public function mostrarPorCidade(string $cidade)
    {
        // Busca os perfis ONDE a cidade corresponde à da URL.
        $acompanhantes = Acompanhante::where('cidade', $cidade)->latest()->get();

        // Reutiliza a nossa view 'vitrine.blade.php', passando os perfis filtrados
        // e também o nome da cidade para usarmos no título.
        return view('vitrine', [
            'acompanhantes' => $acompanhantes,
            'cidadeNome' => $cidade
        ]);
    }

    /**
     * Mostra a página de perfil individual (este método não muda).
     */
    public function show(Acompanhante $acompanhante)
    {
        return view('perfil', ['acompanhante' => $acompanhante]);
    }
}