<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VitrineController; // Importa nosso controller

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aqui é onde definimos as URLs do nosso site.
|
*/

// Rota para a página inicial (vitrine com todos os perfis)
// Aponta a URL "/" para o método 'index' do nosso VitrineController.
Route::get('/', [VitrineController::class, 'index'])->name('vitrine.index');

// Rota para ver um perfil específico
Route::get('/perfil/{acompanhante}', [VitrineController::class, 'show'])->name('vitrine.show');