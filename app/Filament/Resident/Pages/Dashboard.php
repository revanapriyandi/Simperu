<?php

namespace App\Filament\Resident\Pages;

use App\Models\ComplaintLetter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Resident\Widgets\QuickActionsWidget;
use App\Filament\Resident\Widgets\AnnouncementsWidget;
use App\Filament\Resident\Widgets\ComplaintLetterStatsWidget;
use App\Filament\Resident\Widgets\RecentComplaintLettersWidget;
use App\Filament\Resident\Widgets\ResidentComplaintStatsWidget;
use App\Filament\Resident\Widgets\ResidentLatestComplaintsWidget;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.resident.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            QuickActionsWidget::class,
            ResidentComplaintStatsWidget::class,
            ResidentLatestComplaintsWidget::class,
            AnnouncementsWidget::class,
        ];
    }

    public function getTitle(): string | Htmlable
    {
        return '';
    }

    public function getColumns(): int | string | array
    {
        return [
            'default' => 1,
            'sm' => 1,
            'md' => 2,
            'lg' => 3,
            'xl' => 4,
        ];
    }

    public function getLetterCount(): int
    {
        return Cache::remember(
            'user_letter_count_' . Auth::id(),
            now()->addMinutes(5),
            fn() => ComplaintLetter::where('submitted_by', Auth::id())->count()
        );
    }

    public function getPendingCount(): int
    {
        return Cache::remember(
            'user_pending_count_' . Auth::id(),
            now()->addMinutes(5),
            fn() => ComplaintLetter::where('submitted_by', Auth::id())
                ->where('approval_status', 'pending')
                ->count()
        );
    }

    public function getApprovedCount(): int
    {
        return Cache::remember(
            'user_approved_count_' . Auth::id(),
            now()->addMinutes(5),
            fn() => ComplaintLetter::where('submitted_by', Auth::id())
                ->where('approval_status', 'approved')
                ->count()
        );
    }

    public function getThisMonthCount(): int
    {
        return Cache::remember(
            'user_monthly_count_' . Auth::id() . '_' . now()->format('Y-m'),
            now()->addHours(1),
            fn() => ComplaintLetter::where('submitted_by', Auth::id())
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count()
        );
    }
}
