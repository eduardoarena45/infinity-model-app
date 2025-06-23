<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VitrineController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- ROTAS PÚBLICAS (PARA VISITANTES) ---
Route::get('/', [VitrineController::class, 'listarCidades'])->name('cidades.index');
Route::get('/vitrine/{cidade}', [VitrineController::class, 'mostrarPorCidade'])->name('vitrine.por.cidade');
Route::get('/perfil/{acompanhante}', [VitrineController::class, 'show'])->name('vitrine.show');

// --- ROTAS PRIVADAS (PARA ACOMPANHANTES LOGADAS) ---
Route::middleware('auth')->group(function () {
    // Rota padrão do painel
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rotas de gestão do perfil da acompanhante
    Route::get('/meu-perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/meu-perfil', [ProfileController::class, 'update'])->name('profile.update');

    // Rotas da galeria de mídia
    Route::post('/galeria', [ProfileController::class, 'uploadGaleria'])->name('galeria.upload');
    Route::delete('/galeria/{midia}', [ProfileController::class, 'destroyMidia'])->name('galeria.destroy');
});

// Arquivo de rotas de autenticação (login, registro, etc.)
require __DIR__.'/auth.php';