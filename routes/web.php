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
|
| Aqui é onde pode registar as rotas web para a sua aplicação. Estas
| rotas são carregadas pelo RouteServiceProvider e todas elas serão
| atribuídas ao grupo de middleware "web". Faça algo fantástico!
|
*/

// --- ROTAS PÚBLICAS (PARA VISITANTES) ---

// Rota principal que mostra a página de seleção de cidades
Route::get('/', [VitrineController::class, 'listarCidades'])->name('cidades.index');

// Rota que mostra a vitrine de uma cidade específica e lida com filtros
Route::get('/vitrine/{cidade}', [VitrineController::class, 'mostrarPorCidade'])->name('vitrine.por.cidade');

// Rota que mostra o perfil detalhado de uma acompanhante
Route::get('/perfil/{acompanhante}', [VitrineController::class, 'show'])->name('vitrine.show');

// Rota para guardar uma nova avaliação
Route::post('/perfil/{acompanhante}/avaliar', [VitrineController::class, 'storeAvaliacao'])->name('avaliacoes.store');


// --- ROTAS PRIVADAS (PARA UTILIZADORAS LOGADAS) ---
Route::middleware('auth')->group(function () {
    // Painel de controlo principal
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rotas para a gestão do perfil da acompanhante
    Route::get('/meu-perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/meu-perfil', [ProfileController::class, 'update'])->name('profile.update');

    // Rotas para a gestão da galeria de mídia
    Route::get('/minha-galeria', [ProfileController::class, 'gerirGaleria'])->name('galeria.gerir');
    Route::post('/galeria', [ProfileController::class, 'uploadGaleria'])->name('galeria.upload');
    Route::delete('/galeria/{midia}', [ProfileController::class, 'destroyMidia'])->name('galeria.destroy');

    // Rotas para a seleção e assinatura de planos
    Route::get('/escolher-plano', [PlanoController::class, 'selecionar'])->name('planos.selecionar');
    Route::post('/assinar-plano/{plano}', [PlanoController::class, 'assinar'])->name('planos.assinar');

    // Rota da API interna para buscar as cidades de um estado (usada pelo JavaScript)
    Route::get('/api/cidades/{estado}', [LocalController::class, 'getCidadesPorEstado'])->name('api.cidades');
});


// Inclui as rotas de autenticação (login, registo, logout, etc.) do Laravel Breeze
require __DIR__.'/auth.php';
