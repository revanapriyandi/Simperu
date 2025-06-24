<?php

namespace App\Filament\Resident\Widgets;

use App\Models\ComplaintLetter;
use App\Models\PaymentSubmission;
use App\Models\Announcement;
use App\Models\FeeType;
use App\Models\Family;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QuickActionsWidget extends Widget
{
    protected static string $view = 'filament.resident.widgets.quick-actions';
    
    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $user = Auth::user();
        $family = $user->family;
        $currentMonth = Carbon::now();
        
        // Calculate outstanding payments
        $outstandingPayments = [];
        $totalOutstanding = 0;
        
        if ($family) {
            $feeTypes = FeeType::where('is_active', true)->get();
            
            foreach ($feeTypes as $feeType) {
                $payment = PaymentSubmission::where('family_id', $family->id)
                    ->where('fee_type_id', $feeType->id)
                    ->whereYear('payment_date', $currentMonth->year)
                    ->whereMonth('payment_date', $currentMonth->month)
                    ->where('status', 'verified')
                    ->first();
                
                if (!$payment) {
                    $outstandingPayments[] = [
                        'fee_type' => $feeType->name,
                        'amount' => $feeType->amount,
                        'due_date' => $currentMonth->copy()->endOfMonth(),
                    ];
                    $totalOutstanding += $feeType->amount;
                }
            }
        }
        
        return [
            'user' => $user,
            'family' => $family,
            'hasCompleteProfile' => $family && $family->kk_number && $family->head_of_family && $family->house_block,
            'pendingLetters' => ComplaintLetter::where('submitted_by', $user->id)
                ->where('approval_status', 'pending')
                ->count(),
            'approvedLetters' => ComplaintLetter::where('submitted_by', $user->id)
                ->where('approval_status', 'approved')
                ->count(),
            'pendingPayments' => PaymentSubmission::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'verifiedPayments' => PaymentSubmission::where('user_id', $user->id)
                ->where('status', 'verified')
                ->whereYear('payment_date', $currentMonth->year)
                ->whereMonth('payment_date', $currentMonth->month)
                ->count(),
            'unreadAnnouncements' => Announcement::where('is_active', true)
                ->where('created_at', '>=', $user->last_login_at ?? now()->subDays(7))
                ->count(),
            'outstandingPayments' => $outstandingPayments,
            'totalOutstanding' => $totalOutstanding,
            'familyMembersCount' => $family ? $family->members->count() : 0,
        ];
    }
}
