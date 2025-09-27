<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
// =======================================================
// =================== INÍCIO DA ALTERAÇÃO ==================
// Importamos os DOIS widgets para que esta página "saiba" que eles existem.
use App\Filament\Widgets\GanhosStatsOverview;
use App\Filament\Widgets\GanhosChart;
// ==================== FIM DA ALTERAÇÃO =====================

class RelatorioFinanceiro extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Relatório Financeiro';

    protected static ?string $navigationGroup = 'Gestão de Negócio';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Relatório Financeiro';

    protected static string $view = 'filament.pages.relatorio-financeiro';


    // =======================================================
    // =================== INÍCIO DA ALTERAÇÃO ==================
    // A função agora retorna uma lista com os DOIS widgets, na ordem
    // em que queremos que eles apareçam: primeiro os cards, depois o gráfico.
    protected function getHeaderWidgets(): array
    {
        return [
            GanhosStatsOverview::class,
            GanhosChart::class,
        ];
    }
    // =======================================================
    // ==================== FIM DA ALTERAÇÃO =====================
    // =======================================================
}

