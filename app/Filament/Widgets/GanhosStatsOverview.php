<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Assinatura; // Importamos o modelo de Assinatura
use Carbon\Carbon; // Importamos a biblioteca Carbon para trabalhar com datas

class GanhosStatsOverview extends BaseWidget
{
    // Esta função é a responsável por buscar os dados e criar os cards.
    protected function getStats(): array
    {
        // Lógica para calcular os ganhos de hoje
        $ganhosHoje = Assinatura::where('status', 'ativa')
            ->whereDate('updated_at', Carbon::today()) // Filtra por assinaturas ativadas hoje
            ->sum('valor_pago'); // Soma os valores da nossa coluna "cofre"

        // Lógica para calcular os ganhos do mês
        $ganhosMes = Assinatura::where('status', 'ativa')
            ->whereYear('updated_at', Carbon::now()->year)
            ->whereMonth('updated_at', Carbon::now()->month) // Filtra pelo mês e ano atuais
            ->sum('valor_pago');

        // Lógica para calcular o total de ganhos
        $ganhosTotal = Assinatura::where('status', 'ativa')
            ->sum('valor_pago'); // Soma todos os valores pagos de assinaturas ativas

        // Retornamos os três cards com os valores formatados.
        return [
            Stat::make('Ganhos Hoje', 'R$ ' . number_format($ganhosHoje, 2, ',', '.'))
                ->description('Ganhos confirmados hoje')
                ->color('success'),
            Stat::make('Ganhos este Mês', 'R$ ' . number_format($ganhosMes, 2, ',', '.'))
                ->description('Ganhos confirmados no mês atual')
                ->color('success'),
            Stat::make('Ganhos Totais', 'R$ ' . number_format($ganhosTotal, 2, ',', '.'))
                ->description('Total de ganhos confirmados')
                ->color('success'),
        ];
    }
}
