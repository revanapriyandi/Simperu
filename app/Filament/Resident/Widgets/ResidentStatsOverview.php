<?php

namespace App\Filament\Resident\Widgets;

use App\Models\PaymentSubmission;
use App\Models\ComplaintLetter;
use App\Models\Announcement;
use App\Services\PerformanceMonitorService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ResidentStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '30s'; // Reduce polling frequency

    protected function getStats(): array
    {
        $userId = Auth::id();

        if (!$userId) {
            return $this->getEmptyStats();
        }

        // Use optimized service with caching
        $stats = PerformanceMonitorService::optimizeResidentStatsQuery($userId);

        return [
            Stat::make('Pengumuman Aktif', $stats['active_announcements'])
                ->description('Pengumuman terbaru dari pengurus')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('info'),

            Stat::make('Pembayaran Menunggu', $stats['pending_payments'])
                ->description('Pengajuan pembayaran menunggu verifikasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Pembayaran Disetujui', $stats['approved_payments'])
                ->description('Pembayaran yang telah diverifikasi')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Pengaduan Pending', $stats['pending_complaints'])
                ->description('Pengaduan sedang diproses')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning'),

            Stat::make('Pengaduan Selesai', $stats['resolved_complaints'])
                ->description('Pengaduan yang telah diselesaikan')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }

    private function getEmptyStats(): array
    {
        return [
            Stat::make('Pengumuman Aktif', 0)
                ->description('Pengumuman terbaru dari pengurus')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('info'),

            Stat::make('Pembayaran Menunggu', 0)
                ->description('Pengajuan pembayaran menunggu verifikasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Pembayaran Disetujui', 0)
                ->description('Pembayaran yang telah diverifikasi')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Pengaduan Pending', 0)
                ->description('Pengaduan sedang diproses')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning'),

            Stat::make('Pengaduan Selesai', 0)
                ->description('Pengaduan yang telah diselesaikan')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}
