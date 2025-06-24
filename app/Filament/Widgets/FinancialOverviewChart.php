<?php

namespace App\Filament\Widgets;

use App\Models\FinancialTransaction;
use Filament\Widgets\ChartWidget;

class FinancialOverviewChart extends ChartWidget
{
    protected static ?string $heading = 'Overview Keuangan';

    protected static ?int $sort = 8;

    protected function getData(): array
    {
        $income = FinancialTransaction::where('type', 'income')
            ->where('status', 'verified')
            ->sum('amount');

        $expense = FinancialTransaction::where('type', 'expense')
            ->where('status', 'verified')
            ->sum('amount');

        return [
            'datasets' => [
                [
                    'label' => 'Keuangan',
                    'data' => [$income, $expense],
                    'backgroundColor' => [
                        'rgb(34, 197, 94)',  // green - income
                        'rgb(239, 68, 68)',  // red - expense
                    ],
                ],
            ],
            'labels' => ['Pemasukan', 'Pengeluaran'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
