<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\ComplaintLetter;
use App\Models\Announcement;
use Filament\Widgets\Widget;

class SystemOverviewWidget extends Widget
{
    protected static string $view = 'filament.widgets.system-overview';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 10;

    public function getViewData(): array
    {
        return [
            'systemInfo' => [
                'total_users' => User::count(),
                'admin_users' => User::where('role', 'admin')->count(),
                'resident_users' => User::where('role', 'resident')->count(),
                'active_users' => User::where('is_active', true)->count(),
                'verified_users' => User::whereNotNull('email_verified_at')->count(),
            ],
            'contentInfo' => [
                'total_complaints' => ComplaintLetter::count(),
                'pending_complaints' => ComplaintLetter::where('approval_status', 'pending')->count(),
                'approved_complaints' => ComplaintLetter::where('approval_status', 'approved')->count(),
                'total_announcements' => Announcement::count(),
                'active_announcements' => Announcement::where('is_active', true)->count(),
            ],
            'recentActivity' => [
                'new_users_today' => User::whereDate('created_at', today())->count(),
                'new_complaints_today' => ComplaintLetter::whereDate('created_at', today())->count(),
                'new_announcements_today' => Announcement::whereDate('created_at', today())->count(),
            ],
        ];
    }
}
