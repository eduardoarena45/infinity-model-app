<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VitrineController; // Nosso controller da vitrine
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ROTA PRINCIPAL: Agora aponta para o método 'listarCidades' para mostrar as opções.
Route::get('/', [VitrineController::class, 'listarCidades'])->name('cidades.index');

// NOVA ROTA DE VITRINE: Aceita um parâmetro {cidade} na URL.
Route::get('/vitrine/{cidade}', [VitrineController::class, 'mostrarPorCidade'])->name('vitrine.por.cidade');

// Rota para o perfil individual (permanece a mesma)
Route::get('/perfil/{acompanhante}', [VitrineController::class, 'show'])->name('vitrine.show');

// Rota do Dashboard (padrão do Breeze)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Rotas do painel da acompanhante que já criamos
Route::middleware('auth')->group(function () {
    Route::get('/meu-perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/meu-perfil', [ProfileController::class, 'update'])->name('profile.update');
});

// Arquivo de rotas de autenticação do Breeze
require __DIR__.'/auth.php';