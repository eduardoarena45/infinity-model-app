<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Assinatura;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GanhosChart extends ChartWidget
{
    protected static ?string $heading = 'Ganhos por Período';

    // =======================================================
    // =================== INÍCIO DA ALTERAÇÃO ==================
    // Esta é a linha mágica! Ela diz ao widget para ignorar as colunas
    // e ocupar toda a largura disponível na página ('full').
    protected int | string | array $columnSpan = 'full';
    // =======================================================
    // ==================== FIM DA ALTERAÇÃO =====================
    // =======================================================

    // Propriedade para guardar o filtro ativo (ex: 'semana', 'mes', 'ano')
    public ?string $filter = 'semana';

    // Esta função define os botões do filtro no topo do gráfico.
    protected function getFilters(): ?array
    {
        return [
            'semana' => 'Últimos 7 dias',
            'mes' => 'Este Mês',
            'ano' => 'Este Ano',
        ];
    }

    // Esta é a função principal que busca os dados e constrói o gráfico.
    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $datasets = [];
        $labels = [];

        // Lógica para os últimos 7 dias
        if ($activeFilter === 'semana') {
            $data = Assinatura::where('status', 'ativa')
                ->where('updated_at', '>=', Carbon::now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get([
                    DB::raw('DATE(updated_at) as date'),
                    DB::raw('SUM(valor_pago) as aggregate'),
                ])
                ->pluck('aggregate', 'date');

            // Preenche os dias que não tiveram ganhos com o valor 0.
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $labels[] = Carbon::parse($date)->format('d/m');
                $datasets[] = $data->get($date) ?? 0;
            }
        }

        // Lógica para este mês
        elseif ($activeFilter === 'mes') {
            $data = Assinatura::where('status', 'ativa')
                ->whereYear('updated_at', Carbon::now()->year)
                ->whereMonth('updated_at', Carbon::now()->month)
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get([
                    DB::raw('DATE(updated_at) as date'),
                    DB::raw('SUM(valor_pago) as aggregate'),
                ])
                ->pluck('aggregate', 'date');

            // Preenche os dias do mês
            $daysInMonth = Carbon::now()->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $date = Carbon::now()->day($i)->format('Y-m-d');
                $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT);
                $datasets[] = $data->get($date) ?? 0;
            }
        }

        // Lógica para este ano
        elseif ($activeFilter === 'ano') {
            $data = Assinatura::where('status', 'ativa')
                ->whereYear('updated_at', Carbon::now()->year)
                ->groupBy('month')
                ->orderBy('month', 'ASC')
                ->get([
                    DB::raw('MONTH(updated_at) as month'),
                    DB::raw('SUM(valor_pago) as aggregate'),
                ])
                ->pluck('aggregate', 'month');

            // Preenche os meses
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = Carbon::create()->month($i)->format('M');
                $datasets[] = $data->get($i) ?? 0;
            }
        }

        // Retorna os dados no formato que o Filament espera.
        return [
            'datasets' => [
                [
                    'label' => 'Ganhos (R$)',
                    'data' => $datasets,
                    'backgroundColor' => '#2563eb',
                    'borderColor' => '#2563eb',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Define o tipo de gráfico como 'barras'
    }
}

