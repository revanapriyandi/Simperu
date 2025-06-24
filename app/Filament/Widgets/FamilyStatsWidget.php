<?php

namespace App\Filament\Widgets;

use App\Models\Family;
use App\Models\FamilyMember;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FamilyStatsWidget extends BaseWidget
{
    protected static ?int $sort = 9;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Keluarga', Family::count())
                ->description('Keluarga terdaftar')
                ->descriptionIcon('heroicon-o-home')
                ->color('primary'),

            Stat::make('Total Anggota', FamilyMember::count())
                ->description('Anggota keluarga')
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),

            Stat::make('Rata-rata Anggota', number_format(FamilyMember::count() / max(Family::count(), 1), 1))
                ->description('Per keluarga')
                ->descriptionIcon('heroicon-o-calculator')
                ->color('info'),
        ];
    }
}
