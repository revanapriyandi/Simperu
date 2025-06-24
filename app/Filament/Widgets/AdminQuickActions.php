<?php

namespace App\Filament\Widgets;

use App\Models\ComplaintLetter;
use App\Models\PaymentSubmission;
use App\Models\User;
use Filament\Widgets\Widget;

class AdminQuickActions extends Widget
{
    protected static string $view = 'filament.widgets.admin-quick-actions';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public function getViewData(): array
    {
        return [
            'pendingComplaints' => ComplaintLetter::where('approval_status', 'pending')->count(),
            'pendingPayments' => PaymentSubmission::where('status', 'pending')->count(),
            'unverifiedUsers' => User::where('role', 'resident')
                ->whereNull('email_verified_at')
                ->count(),
            'totalResidents' => User::where('role', 'resident')->count(),
        ];
    }
}
