<?php

use App\Http\Controllers\VitrineController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Rota para o perfil individual
Route::get('/perfil/{acompanhante}', [VitrineController::class, 'show'])->name('vitrine.show');

// NOVA ROTA para guardar a avaliação
Route::post('/perfil/{acompanhante}/avaliar', [VitrineController::class, 'storeAvaliacao'])->name('avaliacoes.store');
// ... resto das rotas

// ROTA PRINCIPAL: Mostra a página para selecionar uma cidade
Route::get('/', [VitrineController::class, 'listarCidades'])->name('cidades.index');

// Rota para a vitrine de uma cidade, que agora também lida com filtros
Route::get('/vitrine/{cidade}', [VitrineController::class, 'mostrarPorCidade'])->name('vitrine.por.cidade');

// Rota para o perfil individual
Route::get('/perfil/{acompanhante}', [VitrineController::class, 'show'])->name('vitrine.show');

// Rotas de autenticação e painel da acompanhante...
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    Route::get('/meu-perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/meu-perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/galeria', [ProfileController::class, 'uploadGaleria'])->name('galeria.upload');
    Route::delete('/galeria/{midia}', [ProfileController::class, 'destroyMidia'])->name('galeria.destroy');
});

require __DIR__.'/auth.php';
