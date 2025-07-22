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
        // LÓGICA CORRIGIDA: Busca apenas cidades do estado que têm acompanhantes com status 'aprovado'.
        $cidades = Cidade::where('estado_id', $estado->id)
                         ->whereHas('acompanhantes', function ($query) {
                             $query->where('status', 'aprovado');
                         })
                         ->orderBy('nome')
                         ->get();
        
        return response()->json($cidades);
    }
}