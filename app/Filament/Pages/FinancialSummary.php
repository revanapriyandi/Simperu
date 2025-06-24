<?php

namespace App\Filament\Pages;

use App\Models\FinancialTransaction;
use App\Models\PaymentSubmission;
use App\Models\FeeType;
use App\Models\Family;
use Filament\Pages\Page;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialSummary extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.admin.pages.financial-summary';
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static ?string $navigationLabel = 'Ringkasan Keuangan';
    protected static ?string $navigationGroup = 'Laporan & Analisis';
    protected static ?int $navigationSort = 11;

    protected static ?string $title = 'Ringkasan Keuangan Perumahan';

    public ?array $data = [];
    public $selectedYear = null;
    public $selectedMonth = null;

    public function mount(): void
    {
        $this->selectedYear = now()->year;
        $this->selectedMonth = null;
        $this->data = [
            'year' => $this->selectedYear,
            'month' => $this->selectedMonth,
        ];
        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Filter Laporan')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('year')
                                ->label('Tahun')
                                ->options(function () {
                                    $years = [];
                                    for ($i = 2020; $i <= now()->year + 1; $i++) {
                                        $years[$i] = $i;
                                    }
                                    return $years;
                                })
                                ->default(now()->year)
                                ->reactive()
                                ->afterStateUpdated(function ($state) {
                                    $this->selectedYear = $state;
                                }),
                            
                            Select::make('month')
                                ->label('Bulan (Opsional)')
                                ->options([
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                                ])
                                ->placeholder('Pilih bulan untuk laporan bulanan')
                                ->reactive()
                                ->afterStateUpdated(function ($state) {
                                    $this->selectedMonth = $state;
                                }),
                        ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function getFinancialSummary()
    {
        $query = FinancialTransaction::query();

        if ($this->selectedYear) {
            $query->whereYear('transaction_date', $this->selectedYear);
        }

        if ($this->selectedMonth) {
            $query->whereMonth('transaction_date', $this->selectedMonth);
        }

        $summary = $query->selectRaw('
            type,
            category,
            SUM(amount) as total_amount,
            COUNT(*) as transaction_count
        ')
        ->groupBy('type', 'category')
        ->orderBy('type')
        ->orderBy('category')
        ->get();

        return $summary->groupBy('type');
    }

    public function getPaymentSummary()
    {
        $query = PaymentSubmission::with('feeType');

        if ($this->selectedYear) {
            $query->whereYear('payment_date', $this->selectedYear);
        }

        if ($this->selectedMonth) {
            $query->whereMonth('payment_date', $this->selectedMonth);
        }

        $payments = $query->get();

        return $payments->groupBy('feeType.name')->map(function ($group) {
            $totalFamilies = Family::where('status', 'active')->count();
            
            return [
                'fee_type' => $group->first()->feeType->name,
                'rate_per_month' => $group->first()->feeType->amount,
                'total_paid' => $group->where('status', 'verified')->sum('amount'),
                'total_pending' => $group->where('status', 'pending')->sum('amount'),
                'total_rejected' => $group->where('status', 'rejected')->sum('amount'),
                'families_paid' => $group->where('status', 'verified')->unique('family_id')->count(),
                'total_families' => $totalFamilies,
                'collection_rate' => $totalFamilies > 0 ? round(
                    ($group->where('status', 'verified')->unique('family_id')->count() / $totalFamilies) * 100, 1
                ) : 0,
            ];
        });
    }

    public function getMonthlyComparison()
    {
        if (!$this->selectedYear) {
            return [];
        }

        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $income = FinancialTransaction::whereYear('transaction_date', $this->selectedYear)
                ->whereMonth('transaction_date', $i)
                ->where('type', 'income')
                ->sum('amount');

            $expense = FinancialTransaction::whereYear('transaction_date', $this->selectedYear)
                ->whereMonth('transaction_date', $i)
                ->where('type', 'expense')
                ->sum('amount');

            $months[] = [
                'month' => $this->getMonthName($i),
                'income' => $income,
                'expense' => $expense,
                'balance' => $income - $expense,
            ];
        }

        return $months;
    }

    public function getOutstandingPayments()
    {
        $feeTypes = FeeType::where('is_active', true)->get();
        $families = Family::where('status', 'active')->get();
        $currentMonth = Carbon::now();
        
        $outstanding = [];

        foreach ($feeTypes as $feeType) {
            $totalFamilies = $families->count();
            $paidFamilies = PaymentSubmission::where('fee_type_id', $feeType->id)
                ->whereYear('payment_date', $currentMonth->year)
                ->whereMonth('payment_date', $currentMonth->month)
                ->where('status', 'verified')
                ->distinct('family_id')
                ->count();

            $outstandingFamilies = $totalFamilies - $paidFamilies;
            $outstandingAmount = $outstandingFamilies * $feeType->amount;

            if ($outstandingFamilies > 0) {
                $outstanding[] = [
                    'fee_type' => $feeType->name,
                    'rate' => $feeType->amount,
                    'outstanding_families' => $outstandingFamilies,
                    'outstanding_amount' => $outstandingAmount,
                    'collection_rate' => round(($paidFamilies / $totalFamilies) * 100, 1),
                ];
            }
        }

        return $outstanding;
    }

    private function getMonthName($month)
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $months[$month];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download_report')
                ->label('Download Laporan PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('primary')
                ->action(function () {
                    return $this->downloadReport();
                }),
        ];
    }

    private function downloadReport()
    {
        $summary = $this->getFinancialSummary();
        $payments = $this->getPaymentSummary();
        $monthly = $this->getMonthlyComparison();
        $outstanding = $this->getOutstandingPayments();
        
        // For now, return a simple response. PDF generation can be implemented later
        return response()->json([
            'message' => 'Laporan akan diunduh',
            'data' => [
                'summary' => $summary,
                'payments' => $payments,
                'monthly' => $monthly,
                'outstanding' => $outstanding,
            ]
        ]);
    }
}
