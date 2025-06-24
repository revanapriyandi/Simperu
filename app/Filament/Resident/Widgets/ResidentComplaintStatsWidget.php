<?php

namespace App\Filament\Resident\Widgets;

use App\Models\ComplaintLetter;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ResidentComplaintStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $userId = Auth::id();

        return [
            Stat::make('Total Pengaduan', ComplaintLetter::where('submitted_by', $userId)->count())
                ->description('Surat yang pernah diajukan')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->url(route('filament.resident.resources.complaint-letters.index')),

            Stat::make('Menunggu Persetujuan', ComplaintLetter::where('submitted_by', $userId)->where('approval_status', 'pending')->count())
                ->description('Surat belum disetujui')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Sudah Disetujui', ComplaintLetter::where('submitted_by', $userId)->where('approval_status', 'approved')->count())
                ->description('Surat sudah disetujui')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Dalam Proses', ComplaintLetter::where('submitted_by', $userId)->whereIn('status', ['in_review', 'in_progress'])->count())
                ->description('Sedang ditangani')
                ->descriptionIcon('heroicon-o-cog-6-tooth')
                ->color('info'),
        ];
    }
}
