<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Dashboard Simperu Admin';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.admin-dashboard';

    public function getColumns(): int | string | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 3,
            'xl' => 4,
        ];
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\AdminQuickActions::class,
            \App\Filament\Widgets\AdminStatsOverview::class,
            \App\Filament\Widgets\FamilyStatsWidget::class,
            \App\Filament\Widgets\ComplaintLettersChart::class,
            \App\Filament\Widgets\NewResidentsChart::class,
            \App\Filament\Widgets\PaymentStatusChart::class,
            \App\Filament\Widgets\FinancialOverviewChart::class,
            \App\Filament\Widgets\SystemOverviewWidget::class,
            \App\Filament\Widgets\LatestComplaintLetters::class,
            \App\Filament\Widgets\LatestActivities::class,
            \App\Filament\Widgets\LatestAnnouncements::class,
        ];
    }
}
