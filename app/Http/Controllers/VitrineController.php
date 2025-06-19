<?php

namespace App\Http\Controllers;

use App\Models\Acompanhante; // Importa o Model
use Illuminate\Http\Request;

class VitrineController extends Controller
{
    // Método para a página inicial (a vitrine)
    public function index()
    {
        // Busca todos os perfis no banco de dados, ordenando pelos mais recentes
        $acompanhantes = Acompanhante::latest()->get();

        // Retorna a view 'vitrine.blade.php' e envia a variável $acompanhantes para ela
        return view('vitrine', ['acompanhantes' => $acompanhantes]);
    }

    // Método para a página de perfil individual
    public function show(Acompanhante $acompanhante)
    {
        // Retorna a view 'perfil.blade.php' e envia o perfil encontrado
        return view('perfil', ['acompanhante' => $acompanhante]);
    }
}