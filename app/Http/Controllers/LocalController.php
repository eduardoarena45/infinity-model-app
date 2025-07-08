<?php
namespace App\Http\Controllers;
use App\Models\Local;
use Illuminate\Http\Request;
class LocalController extends Controller
{
    public function getCidadesPorEstado($estado)
    {
        $cidades = Local::where('estado', $estado)->orderBy('cidade')->get();
        return response()->json($cidades);
    }
}