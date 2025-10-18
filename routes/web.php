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
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === ROTA DO SITEMAP XML ===
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// --- ROTAS PÚBLICAS (PARA VISITANTES) ---
Route::get('/', [VitrineController::class, 'listarCidades'])->name('cidades.index');

// Páginas estáticas (legais)
Route::get('/termos-de-servico', function () {
    return view('legal.termos');
})->name('termos');

Route::get('/politica-de-privacidade', function () {
    return view('legal.privacidade');
})->name('privacidade');

// Páginas de acompanhantes por gênero e cidade
Route::get('/acompanhantes/{genero}/{cidade}', [VitrineController::class, 'mostrarPorCidade'])
    ->where(['genero' => 'mulher|homem|trans'])
    ->name('acompanhantes.por.cidade');

// Página de perfil individual
Route::get('/acompanhante/{acompanhante}', [VitrineController::class, 'show'])->name('acompanhantes.show');

// Avaliação de perfil
Route::post('/acompanhante/{acompanhante}/avaliar', [VitrineController::class, 'storeAvaliacao'])->name('avaliacoes.store');

// APIs públicas
Route::get('/api/cidades/{estado}', [LocalController::class, 'getCidadesPorEstado'])->name('api.cidades');
Route::get('/api/acompanhante/{acompanhante}/galeria', [VitrineController::class, 'getGaleriaFotos'])->name('api.acompanhante.galeria');

// --- REDIRECIONAMENTOS ANTIGOS (SEO) ---
// Caso alguém ainda acesse URLs antigas de “vitrine”, redireciona para o novo formato.
Route::get('/vitrine/{genero}/{cidade}', function ($genero, $cidade) {
    return redirect()->route('acompanhantes.por.cidade', [
        'genero' => $genero,
        'cidade' => $cidade
    ], 301);
});

Route::get('/perfil/{acompanhante}', function ($acompanhante) {
    return redirect()->route('acompanhantes.show', $acompanhante, 301);
});

// --- ROTAS PRIVADAS (PARA UTILIZADORAS LOGADAS) ---
Route::middleware(['auth', 'nocache'])->group(function () {

    // =======================================================
    // ================ INÍCIO DA REORGANIZAÇÃO ================
    // =======================================================

    Route::middleware('onboarding.check')->group(function () {

        Route::get('/dashboard', function () {
            $user = Auth::user();
            $acompanhante = $user->acompanhante()->with('cidade', 'avaliacoes')->firstOrCreate([]);
            $viewsToday = $acompanhante->profileViews()->whereDate('created_at', Carbon::today())->count();
            $viewsThisMonth = $acompanhante->profileViews()->whereMonth('created_at', Carbon::now()->month)->count();
            $totalViews = $acompanhante->profileViews()->count();
            $viewsLast7Days = $acompanhante->profileViews()
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get()
                ->pluck('count', 'date');

            $chartData = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $chartData['labels'][] = Carbon::parse($date)->format('d/m');
                $chartData['data'][] = $viewsLast7Days->get($date, 0);
            }

            return view('dashboard', [
                'acompanhante' => $acompanhante,
                'viewsToday' => $viewsToday,
                'viewsThisMonth' => $viewsThisMonth,
                'totalViews' => $totalViews,
                'chartData' => $chartData,
            ]);
        })->name('dashboard');

        // A rota "Meu Plano"
        Route::get('/meu-plano', [PlanoController::class, 'selecionar'])->name('planos.selecionar');

        // Galeria
        Route::get('/minha-galeria', [ProfileController::class, 'gerirGaleria'])->name('galeria.gerir');
        Route::post('/galeria', [ProfileController::class, 'uploadGaleria'])->name('galeria.upload');
        Route::post('/galeria/videos', [ProfileController::class, 'uploadVideo'])->name('galeria.upload.video');
        Route::delete('/galeria/{media}', [ProfileController::class, 'destroyMidia'])->name('galeria.destroy');

        // Notificações e disponibilidade
        Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::get('/disponibilidade', [DisponibilidadeController::class, 'edit'])->name('disponibilidade.edit');
        Route::post('/disponibilidade', [DisponibilidadeController::class, 'update'])->name('disponibilidade.update');
    });

    // --- FUNIL DE ONBOARDING ---
    Route::post('/assinar-plano/{plano}', [PlanoController::class, 'assinar'])->name('planos.assinar');
    Route::get('/planos/pagamento/{assinatura}', [PlanoController::class, 'mostrarPagamento'])->name('planos.pagamento');

    Route::get('/meu-perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/meu-perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/meu-perfil/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');

    // =======================================================
    // ================== FIM DA REORGANIZAÇÃO =================
    // =======================================================
});

require __DIR__ . '/auth.php';
