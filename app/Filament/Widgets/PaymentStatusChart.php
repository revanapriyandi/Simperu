<?php

namespace App\Filament\Widgets;

use App\Models\PaymentSubmission;
use Filament\Widgets\ChartWidget;

class PaymentStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Status Pembayaran';

    protected static ?int $sort = 5;

    protected function getData(): array
    {
        $pending = PaymentSubmission::where('status', 'pending')->count();
        $approved = PaymentSubmission::where('status', 'approved')->count();
        $rejected = PaymentSubmission::where('status', 'rejected')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Status Pembayaran',
                    'data' => [$pending, $approved, $rejected],
                    'backgroundColor' => [
                        'rgb(251, 191, 36)', // warning - pending
                        'rgb(34, 197, 94)',  // success - approved
                        'rgb(239, 68, 68)',  // danger - rejected
                    ],
                ],
            ],
            'labels' => ['Pending', 'Disetujui', 'Ditolak'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
