<?php

use App\Http\Controllers\LocalController;
use App\Http\Controllers\PlanoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VitrineController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DisponibilidadeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

Route::get('/vitrine/{genero}/{cidade}', [VitrineController::class, 'mostrarPorCidade'])->name('vitrine.por.cidade');
Route::get('/perfil/{acompanhante}', [VitrineController::class, 'show'])->name('vitrine.show');
Route::post('/perfil/{acompanhante}/avaliar', [VitrineController::class, 'storeAvaliacao'])->name('avaliacoes.store');

// --- ROTA DA API DE CIDADES (AGORA É PÚBLICA) ---
Route::get('/api/cidades/{estado}', [LocalController::class, 'getCidadesPorEstado'])->name('api.cidades');


// --- ROTAS PRIVADAS (PARA UTILIZADORAS LOGADAS) ---
Route::middleware('auth')->group(function () {

    // ROTA DO DASHBOARD ATUALIZADA COM A LÓGICA DAS ESTATÍSTICAS
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $acompanhante = $user->acompanhante()->with('cidade', 'avaliacoes')->firstOrCreate([]);

        // --- LÓGICA DAS ESTATÍSTICAS ---
        $viewsToday = $acompanhante->profileViews()->whereDate('created_at', Carbon::today())->count();
        $viewsThisMonth = $acompanhante->profileViews()->whereMonth('created_at', Carbon::now()->month)->count();
        $totalViews = $acompanhante->profileViews()->count();

        // Dados para o gráfico (últimos 7 dias)
        $viewsLast7Days = $acompanhante->profileViews()
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get()
            ->pluck('count', 'date');

        // Preencher os dias sem visualizações com 0
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartData['labels'][] = Carbon::parse($date)->format('d/m');
            $chartData['data'][] = $viewsLast7Days->get($date, 0);
        }
        // --- FIM DA LÓGICA ---

        return view('dashboard', [
            'acompanhante' => $acompanhante,
            'viewsToday' => $viewsToday,
            'viewsThisMonth' => $viewsThisMonth,
            'totalViews' => $totalViews,
            'chartData' => $chartData,
        ]);
    })->name('dashboard');

    Route::get('/meu-perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/meu-perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/meu-perfil/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');

    Route::get('/minha-galeria', [ProfileController::class, 'gerirGaleria'])->name('galeria.gerir');
    Route::post('/galeria', [ProfileController::class, 'uploadGaleria'])->name('galeria.upload');
    Route::post('/galeria/videos', [ProfileController::class, 'uploadVideo'])->name('galeria.upload.video');
    Route::delete('/galeria/{media}', [ProfileController::class, 'destroyMidia'])->name('galeria.destroy');

    Route::get('/escolher-plano', [PlanoController::class, 'selecionar'])->name('planos.selecionar');
    Route::post('/assinar-plano/{plano}', [PlanoController::class, 'assinar'])->name('planos.assinar');
    Route::get('/planos/pagamento/{assinatura}', [PlanoController::class, 'mostrarPagamento'])->name('planos.pagamento');

    // NOVA ROTA PARA MARCAR NOTIFICAÇÕES COMO LIDAS
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

    // NOVAS ROTAS PARA A PÁGINA DE DISPONIBILIDADE
    Route::get('/disponibilidade', [DisponibilidadeController::class, 'edit'])->name('disponibilidade.edit');
    Route::post('/disponibilidade', [DisponibilidadeController::class, 'update'])->name('disponibilidade.update');
});

// =======================================================================
// INÍCIO DO CÓDIGO ADICIONADO
// =======================================================================

// --- ROTAS DO PAINEL ADMIN (GUNODIGITAL) ---
// Adicionando as rotas que faltavam para gerenciar estados e cidades
Route::middleware(['auth'])->prefix('guno')->name('guno.')->group(function () {
    Route::resource('estados', LocalController::class);
    Route::post('estados/{estado}/cidades', [LocalController::class, 'storeCidade'])->name('cidades.store');
    Route::delete('cidades/{cidade}', [LocalController::class, 'destroyCidade'])->name('cidades.destroy');
});

// =======================================================================
// FIM DO CÓDIGO ADICIONADO
// =======================================================================


// Inclui as rotas de autenticação
require __DIR__.'/auth.php';
