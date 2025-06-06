<?php

namespace App\Filament\Resident\Pages;

use App\Filament\Resident\Widgets\ResidentStatsOverview;
use App\Filament\Resident\Widgets\LatestAnnouncements;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.resident.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            ResidentStatsOverview::class,
            LatestAnnouncements::class,
        ];
    }
}
