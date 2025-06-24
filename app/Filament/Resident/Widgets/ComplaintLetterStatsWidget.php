<?php

namespace App\Filament\Resident\Widgets;

use App\Models\ComplaintLetter;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ComplaintLetterStatsWidget extends BaseStatsOverviewWidget
{
    protected function getStats(): array
    {
        $userId = Auth::id();

        $totalLetters = ComplaintLetter::where('submitted_by', $userId)->count();
        $pendingLetters = ComplaintLetter::where('submitted_by', $userId)
            ->where('approval_status', 'pending')
            ->count();
        $approvedLetters = ComplaintLetter::where('submitted_by', $userId)
            ->where('approval_status', 'approved')
            ->count();
        $thisMonthLetters = ComplaintLetter::where('submitted_by', $userId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            Stat::make('Total Surat', $totalLetters)
                ->description('Semua surat yang pernah diajukan')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
                
            Stat::make('Menunggu Persetujuan', $pendingLetters)
                ->description('Surat yang belum disetujui')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
                
            Stat::make('Disetujui', $approvedLetters)
                ->description('Surat yang sudah disetujui')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
                
            Stat::make('Bulan Ini', $thisMonthLetters)
                ->description('Surat yang diajukan bulan ini')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),
        ];
    }
}
