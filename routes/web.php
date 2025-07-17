<?php

use App\Http\Controllers\LocalController;
use App\Http\Controllers\PlanoController;
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

Route::get('/termos-de-servico', function () {
    return view('legal.termos');
})->name('termos');

Route::get('/politica-de-privacidade', function () {
    return view('legal.privacidade');
})->name('privacidade');

Route::get('/vitrine/{cidade}', [VitrineController::class, 'mostrarPorCidade'])->name('vitrine.por.cidade');
Route::get('/perfil/{acompanhante}', [VitrineController::class, 'show'])->name('vitrine.show');
Route::post('/perfil/{acompanhante}/avaliar', [VitrineController::class, 'storeAvaliacao'])->name('avaliacoes.store');


// --- ROTAS PRIVADAS (PARA UTILIZADORAS LOGADAS) ---
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rotas para a gestão do perfil da acompanhante
    Route::get('/meu-perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/meu-perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/meu-perfil/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');

    // Rotas para a gestão da galeria de mídia (AGORA CORRIGIDAS)
    Route::get('/minha-galeria', [ProfileController::class, 'gerirGaleria'])->name('galeria.gerir');
    Route::post('/galeria', [ProfileController::class, 'uploadGaleria'])->name('galeria.upload');
    Route::delete('/galeria/{media}', [ProfileController::class, 'destroyMidia'])->name('galeria.destroy');

    // Rotas para a seleção e assinatura de planos
    Route::get('/escolher-plano', [PlanoController::class, 'selecionar'])->name('planos.selecionar');
    Route::post('/assinar-plano/{plano}', [PlanoController::class, 'assinar'])->name('planos.assinar');

    // ROTA DA API INTERNA PARA BUSCAR AS CIDADES
    Route::get('/api/cidades/{estado}', [LocalController::class, 'getCidadesPorEstado'])->name('api.cidades');
});


// Inclui as rotas de autenticação (login, registo, logout, etc.)
require __DIR__.'/auth.php';