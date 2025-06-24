<?php

namespace App\Filament\Resident\Pages\Reports;

use App\Models\FinancialTransaction;
use App\Models\PaymentSubmission;
use App\Models\FeeType;
use Filament\Pages\Page;
use Filament\Widgets\ChartWidget;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Filament\Support\Enums\MaxWidth;

class FinancialReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Laporan Keuangan';
    protected static ?string $title = 'Laporan Keuangan Keluarga';
    protected static ?string $navigationGroup = 'Laporan & Administrasi';
    protected static ?int $navigationSort = 10;
    protected static string $view = 'filament.resident.pages.reports.financial-report';

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
                                    1 => 'Januari',
                                    2 => 'Februari',
                                    3 => 'Maret',
                                    4 => 'April',
                                    5 => 'Mei',
                                    6 => 'Juni',
                                    7 => 'Juli',
                                    8 => 'Agustus',
                                    9 => 'September',
                                    10 => 'Oktober',
                                    11 => 'November',
                                    12 => 'Desember',
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

    public function getPaymentHistory()
    {
        $query = PaymentSubmission::with(['feeType', 'family'])
            ->where('family_id', Auth::user()->family?->id);

        if ($this->selectedYear) {
            $query->whereYear('payment_date', $this->selectedYear);
        }

        if ($this->selectedMonth) {
            $query->whereMonth('payment_date', $this->selectedMonth);
        }

        return $query->orderBy('payment_date', 'desc')->get();
    }

    public function getPaymentSummary()
    {
        $family = Auth::user()->family;
        if (!$family) return collect();

        $query = PaymentSubmission::where('family_id', $family->id);

        if ($this->selectedYear) {
            $query->whereYear('payment_date', $this->selectedYear);
        }

        if ($this->selectedMonth) {
            $query->whereMonth('payment_date', $this->selectedMonth);
        }

        $payments = $query->with('feeType')->get();

        return $payments->groupBy('feeType.name')->map(function ($group) {
            return [
                'fee_type' => $group->first()->feeType->name,
                'amount' => $group->first()->feeType->amount,
                'total_paid' => $group->where('status', 'verified')->sum('amount'),
                'total_pending' => $group->where('status', 'pending')->sum('amount'),
                'total_rejected' => $group->where('status', 'rejected')->sum('amount'),
                'payments_count' => $group->count(),
                'verified_count' => $group->where('status', 'verified')->count(),
                'pending_count' => $group->where('status', 'pending')->count(),
            ];
        });
    }

    public function getMonthlyPaymentChart()
    {
        $family = Auth::user()->family;
        if (!$family) return [];

        $startDate = Carbon::create($this->selectedYear ?: now()->year, 1, 1);
        $endDate = $startDate->copy()->endOfYear();

        $payments = PaymentSubmission::where('family_id', $family->id)
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'verified')
            ->selectRaw('MONTH(payment_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = [
                'month' => $this->getMonthName($i),
                'amount' => $payments[$i] ?? 0,
            ];
        }

        return $chartData;
    }

    private function getMonthName($month)
    {
        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Ags',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];
        return $months[$month];
    }

    public function getOutstandingPayments()
    {
        $family = Auth::user()->family;
        if (!$family) return collect();

        $feeTypes = FeeType::where('is_active', true)->get();
        $currentMonth = now();
        
        $outstanding = collect();

        foreach ($feeTypes as $feeType) {
            // Check if payment exists for current month
            $payment = PaymentSubmission::where('family_id', $family->id)
                ->where('fee_type_id', $feeType->id)
                ->whereYear('payment_date', $currentMonth->year)
                ->whereMonth('payment_date', $currentMonth->month)
                ->where('status', 'verified')
                ->first();

            if (!$payment) {
                $outstanding->push([
                    'fee_type' => $feeType->name,
                    'amount' => $feeType->amount,
                    'due_date' => $currentMonth->copy()->endOfMonth(),
                    'days_overdue' => $currentMonth->day > $currentMonth->daysInMonth ? 
                        $currentMonth->diffInDays($currentMonth->copy()->endOfMonth()) : 0,
                ]);
            }
        }

        return $outstanding;
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('download_report')
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
        // Generate PDF report logic here
        $family = Auth::user()->family;
        $payments = $this->getPaymentHistory();
        $summary = $this->getPaymentSummary();
        
        $pdf = \PDF::loadView('pdf.resident-financial-report', [
            'family' => $family,
            'payments' => $payments,
            'summary' => $summary,
            'selectedYear' => $this->selectedYear,
            'selectedMonth' => $this->selectedMonth,
            'generatedAt' => now(),
        ]);

        $filename = 'laporan-keuangan-' . $family->head_of_family . '-' . 
                   ($this->selectedMonth ? $this->selectedMonth . '-' : '') . 
                   $this->selectedYear . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }
}
