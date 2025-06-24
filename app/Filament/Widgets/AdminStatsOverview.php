<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Family;
use App\Models\ComplaintLetter;
use App\Models\Announcement;
use App\Models\PaymentSubmission;
use App\Models\FinancialTransaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Warga', User::where('role', 'resident')->count())
                ->description('Warga terdaftar')
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),

            Stat::make('Total Keluarga', Family::count())
                ->description('Keluarga terdaftar')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('info'),

            Stat::make('Surat Pengaduan', ComplaintLetter::count())
                ->description(ComplaintLetter::where('approval_status', 'pending')->count() . ' menunggu persetujuan')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('warning'),

            Stat::make('Pengumuman Aktif', Announcement::where('is_active', true)->count())
                ->description('Pengumuman yang dipublikasikan')
                ->descriptionIcon('heroicon-o-megaphone')
                ->color('primary'),

            Stat::make('Pembayaran Pending', PaymentSubmission::where('status', 'pending')->count())
                ->description('Menunggu verifikasi')
                ->descriptionIcon('heroicon-o-credit-card')
                ->color('danger'),

            Stat::make('Total Transaksi', number_format(FinancialTransaction::sum('amount'), 0, ',', '.'))
                ->description('Total nilai transaksi')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),
        ];
    }
}
