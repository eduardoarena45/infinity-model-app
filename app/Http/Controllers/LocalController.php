<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\Cidade;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LocalController extends Controller
{
    /**
     * Display a listing of the resource.
     * Mostra a lista de todos os estados.
     */
    public function index()
    {
        $estados = Estado::orderBy('nome')->paginate(10);
        return view('guno.locais.index', compact('estados')); // Certifique-se que a view existe em resources/views/guno/locais/index.blade.php
    }

    /**
     * Show the form for creating a new resource.
     * Mostra o formulário para criar um novo estado.
     */
    public function create()
    {
        return view('guno.locais.create'); // Certifique-se que a view existe em resources/views/guno/locais/create.blade.php
    }

    /**
     * Store a newly created resource in storage.
     * Salva um novo estado no banco de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:estados,nome',
            'uf' => 'required|string|size:2|unique:estados,uf',
        ]);

        Estado::create($request->all());

        return redirect()->route('guno.estados.index')->with('success', 'Estado criado com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     * Mostra a página de edição de um estado e suas cidades.
     */
    public function edit(Estado $estado)
    {
        $cidades = $estado->cidades()->orderBy('nome')->get();
        return view('guno.locais.edit', compact('estado', 'cidades')); // Esta parece ser a view que você já tem
    }

    /**
     * Update the specified resource in storage.
     * Atualiza um estado existente no banco de dados.
     */
    public function update(Request $request, Estado $estado)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:estados,nome,' . $estado->id,
            'uf' => 'required|string|size:2|unique:estados,uf,' . $estado->id,
        ]);

        $estado->update($request->all());

        return redirect()->back()->with('success', 'Estado atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     * Apaga um estado do banco de dados.
     */
    public function destroy(Estado $estado)
    {
        $estado->delete();
        return redirect()->route('guno.estados.index')->with('success', 'Estado apagado com sucesso!');
    }

    /**
     * Store a new Cidade for a given Estado.
     * Salva uma nova cidade ligada a um estado.
     */
    public function storeCidade(Request $request, Estado $estado)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $estado->cidades()->create($request->only('nome'));

        return redirect()->back()->with('success', 'Cidade adicionada com sucesso!');
    }

    /**
     * Destroy a Cidade.
     * Apaga uma cidade.
     */
    public function destroyCidade(Cidade $cidade)
    {
        $cidade->delete();
        return redirect()->back()->with('success', 'Cidade apagada com sucesso!');
    }

    /**
     * API endpoint to get Cidades for a given Estado.
     * Retorna uma lista de cidades para um determinado estado como JSON.
     */
    public function getCidadesPorEstado(Estado $estado): JsonResponse
    {
        $cidades = $estado->cidades()->orderBy('nome')->get(['id', 'nome']);
        return response()->json($cidades);
    }
}
