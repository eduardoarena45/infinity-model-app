<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\Cidade; // Importa o Model Cidade
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LocalController extends Controller
{
    /**
     * Retorna uma lista de cidades para um determinado estado como JSON.
     * Usa Route-Model Binding para encontrar o estado automaticamente pelo ID.
     */
    public function getCidadesPorEstado(Estado $estado): JsonResponse
    {
        // CORREÇÃO: A lógica agora busca TODAS as cidades do estado, sem filtros.
        // Isso permite que a acompanhante selecione qualquer cidade que o admin cadastrou.
        $cidades = $estado->cidades()->orderBy('nome')->get();
        
        return response()->json($cidades);
    }
}