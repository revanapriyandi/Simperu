<?php

namespace App\Filament\Widgets;

use App\Models\ComplaintLetter;
use App\Models\PaymentSubmission;
use App\Models\Family;
use App\Models\FinancialTransaction;
use App\Models\FeeType;
use App\Models\User;
use Filament\Widgets\Widget;
use Carbon\Carbon;

class AdminQuickActions extends Widget
{
    protected static string $view = 'filament.widgets.admin-quick-actions';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public function getViewData(): array
    {
        $currentMonth = Carbon::now();
        
        // Get outstanding payments for current month
        $feeTypes = FeeType::where('is_active', true)->get();
        $totalFamilies = Family::where('status', 'active')->count();
        $outstandingFamilies = 0;
        $totalOutstanding = 0;
        
        foreach ($feeTypes as $feeType) {
            $paidFamilies = PaymentSubmission::where('fee_type_id', $feeType->id)
                ->whereYear('payment_date', $currentMonth->year)
                ->whereMonth('payment_date', $currentMonth->month)
                ->where('status', 'verified')
                ->distinct('family_id')
                ->count();
            
            $outstanding = $totalFamilies - $paidFamilies;
            $outstandingFamilies += $outstanding;
            $totalOutstanding += $outstanding * $feeType->amount;
        }
        
        return [
            'pendingComplaints' => ComplaintLetter::where('approval_status', 'pending')->count(),
            'pendingPayments' => PaymentSubmission::where('status', 'pending')->count(),
            'unverifiedUsers' => User::where('role', 'resident')
                ->whereNull('email_verified_at')
                ->count(),
            'totalResidents' => User::where('role', 'resident')->count(),
            'totalFamilies' => $totalFamilies,
            'outstandingFamilies' => $outstandingFamilies,
            'totalOutstanding' => $totalOutstanding,
            'monthlyIncome' => FinancialTransaction::where('type', 'income')
                ->whereYear('transaction_date', $currentMonth->year)
                ->whereMonth('transaction_date', $currentMonth->month)
                ->sum('amount'),
            'monthlyExpense' => FinancialTransaction::where('type', 'expense')
                ->whereYear('transaction_date', $currentMonth->year)
                ->whereMonth('transaction_date', $currentMonth->month)
                ->sum('amount'),
        ];
    }
}
