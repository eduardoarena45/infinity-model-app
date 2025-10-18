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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- ROTAS PÚBLICAS (PARA VISITANTES) ---
Route::get('/', [VitrineController::class, 'listarCidades'])->name('cidades.index');
// Rota que serve o sitemap.xml dinâmico
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/termos-de-servico', function () { return view('legal.termos'); })->name('termos');
Route::get('/politica-de-privacidade', function () { return view('legal.privacidade'); })->name('privacidade');
// Páginas de acompanhantes por gênero e cidade
Route::get('/acompanhantes/{genero}/{cidade}', [VitrineController::class, 'mostrarPorCidade'])
    ->where(['genero' => 'mulher|homem|trans'])
    ->name('acompanhantes.por.cidade');

// Página de perfil individual
Route::get('/acompanhante/{acompanhante}', [VitrineController::class, 'show'])->name('acompanhantes.show');

// Avaliação de perfil
Route::post('/acompanhante/{acompanhante}/avaliar', [VitrineController::class, 'storeAvaliacao'])->name('avaliacoes.store');

Route::get('/api/cidades/{estado}', [LocalController::class, 'getCidadesPorEstado'])->name('api.cidades');
Route::get('/api/acompanhante/{acompanhante}/galeria', [VitrineController::class, 'getGaleriaFotos'])->name('api.acompanhante.galeria');


// --- ROTAS PRIVADAS (PARA UTILIZADORAS LOGADAS) ---
Route::middleware(['auth', 'nocache'])->group(function () {

    // =======================================================
    // ================ INÍCIO DA REORGANIZAÇÃO ================
    // =======================================================

    // --- GRUPO 1: ROTAS DO PAINEL PRINCIPAL (PROTEGIDAS PELO ONBOARDING CHECK) ---
    // Todas as rotas do painel agora ficam DENTRO deste grupo.
    Route::middleware('onboarding.check')->group(function () {

        Route::get('/dashboard', function () {
            // ... (código da rota dashboard continua igual)
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

        // A rota "Meu Plano" foi movida para DENTRO do grupo protegido.
        Route::get('/meu-plano', [PlanoController::class, 'selecionar'])->name('planos.selecionar');

        // Rotas de gestão da galeria
        Route::get('/minha-galeria', [ProfileController::class, 'gerirGaleria'])->name('galeria.gerir');
        Route::post('/galeria', [ProfileController::class, 'uploadGaleria'])->name('galeria.upload');
        Route::post('/galeria/videos', [ProfileController::class, 'uploadVideo'])->name('galeria.upload.video');
        Route::delete('/galeria/{media}', [ProfileController::class, 'destroyMidia'])->name('galeria.destroy');

        // Outras rotas do painel
        Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::get('/disponibilidade', [DisponibilidadeController::class, 'edit'])->name('disponibilidade.edit');
        Route::post('/disponibilidade', [DisponibilidadeController::class, 'update'])->name('disponibilidade.update');
    });

    // --- GRUPO 2: ROTAS DO FUNIL DE ONBOARDING (NÃO PROTEGIDAS PELO CHECK) ---
    // Estas rotas são as únicas que o "porteiro" deixa passar no início.

    // A rota para escolher o plano foi movida para o grupo protegido,
    // mas as rotas para assinar e pagar ficam aqui.
    Route::post('/assinar-plano/{plano}', [PlanoController::class, 'assinar'])->name('planos.assinar');
    Route::get('/planos/pagamento/{assinatura}', [PlanoController::class, 'mostrarPagamento'])->name('planos.pagamento');

    // As rotas de edição de perfil também ficam fora do grupo principal
    // para que o middleware as permita aceder durante o onboarding.
    Route::get('/meu-perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/meu-perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/meu-perfil/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');

    // =======================================================
    // ================== FIM DA REORGANIZAÇÃO =================
    // =======================================================
});


// Inclui as rotas de autenticação
require __DIR__.'/auth.php';
