<?php

namespace App\Filament\Resident\Widgets;

use App\Models\PaymentSubmission;
use App\Models\ComplaintLetter;
use App\Models\Announcement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ResidentStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = Auth::id();

        // Count payment submissions by status
        $pendingPayments = PaymentSubmission::whereHas('family.user', function ($query) use ($userId) {
            $query->where('id', $userId);
        })->where('status', 'pending')->count();

        $approvedPayments = PaymentSubmission::whereHas('family.user', function ($query) use ($userId) {
            $query->where('id', $userId);
        })->where('status', 'approved')->count();

        // Count complaint letters by status
        $pendingComplaints = ComplaintLetter::where('submitted_by', $userId)
            ->whereIn('status', ['submitted', 'in_review'])->count();

        $resolvedComplaints = ComplaintLetter::where('submitted_by', $userId)
            ->where('status', 'resolved')->count();

        // Count active announcements
        $activeAnnouncements = Announcement::where('is_active', true)
            ->where('publish_date', '<=', now())
            ->count();

        return [
            Stat::make('Pengumuman Aktif', $activeAnnouncements)
                ->description('Pengumuman terbaru dari pengurus')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('info'),

            Stat::make('Pembayaran Menunggu', $pendingPayments)
                ->description('Pengajuan pembayaran menunggu verifikasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Pembayaran Disetujui', $approvedPayments)
                ->description('Pembayaran yang telah diverifikasi')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Pengaduan Pending', $pendingComplaints)
                ->description('Pengaduan sedang diproses')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning'),

            Stat::make('Pengaduan Selesai', $resolvedComplaints)
                ->description('Pengaduan yang telah diselesaikan')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}
