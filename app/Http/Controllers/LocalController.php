<?php

namespace App\Http\Controllers;

use App\Models\Estado;
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
        // Carrega as cidades relacionadas ao estado, ordena por nome e retorna como JSON
        $cidades = $estado->cidades()->orderBy('nome')->get();
        
        return response()->json($cidades);
    }
}