<?php

namespace App\Filament\Resident\Widgets;

use App\Models\ComplaintLetter;
use App\Models\PaymentSubmission;
use App\Models\Announcement;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class QuickActionsWidget extends Widget
{
    protected static string $view = 'filament.resident.widgets.quick-actions';
    
    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $user = Auth::user();
        
        return [
            'user' => $user,
            'pendingLetters' => ComplaintLetter::where('submitted_by', $user->id)
                ->where('approval_status', 'pending')
                ->count(),
            'pendingPayments' => PaymentSubmission::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'unreadAnnouncements' => Announcement::where('is_active', true)
                ->where('created_at', '>=', $user->last_login_at ?? now()->subDays(7))
                ->count(),
        ];
    }
}
