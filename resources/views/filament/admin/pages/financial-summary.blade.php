<x-filament-panels::page>
    @push('styles')
        <style>
            .summary-header {
                background: linear-gradient(135deg, #059669 0%, #10b981 100%);
                border-radius: 12px;
                padding: 2rem;
                margin-bottom: 2rem;
                color: white;
            }
            
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1rem;
                margin-top: 1.5rem;
            }
            
            .stat-card {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 8px;
                padding: 1rem;
                border: 1px solid rgba(255, 255, 255, 0.2);
                text-align: center;
            }
            
            .stat-value {
                font-size: 1.5rem;
                font-weight: bold;
                margin-bottom: 0.25rem;
            }
            
            .stat-label {
                font-size: 0.875rem;
                opacity: 0.8;
            }
            
            .summary-section {
                background: white;
                border-radius: 12px;
                padding: 1.5rem;
                margin-bottom: 1.5rem;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }
            
            .section-title {
                font-size: 1.125rem;
                font-weight: 600;
                margin-bottom: 1rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
        </style>
    @endpush

    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="summary-header">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <x-heroicon-o-chart-pie class="h-6 w-6 text-white" />
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">
                                Ringkasan Keuangan Perumahan
                            </h1>
                            <p class="text-white/80 text-sm">
                                Villa Windaro Permai • Periode: 
                                @if($this->selectedMonth)
                                    {{ $this->getMonthName($this->selectedMonth) }} {{ $this->selectedYear }}
                                @else
                                    Tahun {{ $this->selectedYear }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <p class="text-white/90 leading-relaxed max-w-2xl">
                        Laporan komprehensif kondisi keuangan perumahan meliputi pemasukan, pengeluaran, 
                        dan tingkat pembayaran iuran warga.
                    </p>
                </div>
            </div>

            <!-- Key Stats -->
            <div class="stats-grid">
                @php
                    $summary = $this->getFinancialSummary();
                    $payments = $this->getPaymentSummary();
                    $outstanding = $this->getOutstandingPayments();
                    
                    $totalIncome = $summary->get('income', collect())->sum('total_amount');
                    $totalExpense = $summary->get('expense', collect())->sum('total_amount');
                    $totalBalance = $totalIncome - $totalExpense;
                    $totalCollected = $payments->sum('total_paid');
                @endphp
                
                <div class="stat-card">
                    <div class="stat-value">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                    <div class="stat-label">Total Pemasukan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
                    <div class="stat-label">Total Pengeluaran</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">Rp {{ number_format($totalBalance, 0, ',', '.') }}</div>
                    <div class="stat-label">Saldo Bersih</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $outstanding->count() }}</div>
                    <div class="stat-label">Jenis Iuran Belum Terbayar</div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="summary-section">
            {{ $this->form }}
        </div>

        <!-- Outstanding Payments Summary -->
        @if($outstanding->count() > 0)
        <div class="summary-section">
            <div class="section-title">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-red-600" />
                Tunggakan Iuran Bulan Ini
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($outstanding as $item)
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <h4 class="font-semibold text-red-900 mb-2">{{ $item['fee_type'] }}</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-red-700">Keluarga belum bayar:</span>
                            <span class="font-medium text-red-900">{{ $item['outstanding_families'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-red-700">Total tunggakan:</span>
                            <span class="font-bold text-red-900">Rp {{ number_format($item['outstanding_amount'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-red-700">Tingkat pembayaran:</span>
                            <span class="font-medium text-red-900">{{ $item['collection_rate'] }}%</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Payment Summary -->
        <div class="summary-section">
            <div class="section-title">
                <x-heroicon-o-banknotes class="w-5 h-5 text-green-600" />
                Ringkasan Pembayaran Iuran
            </div>
            
            @if($payments->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($payments as $payment)
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <h4 class="font-semibold text-gray-900 mb-3">{{ $payment['fee_type'] }}</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Iuran per bulan:</span>
                                <span class="font-medium">Rp {{ number_format($payment['rate_per_month'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total terkumpul:</span>
                                <span class="font-bold text-green-600">Rp {{ number_format($payment['total_paid'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Keluarga bayar:</span>
                                <span class="font-medium">{{ $payment['families_paid'] }}/{{ $payment['total_families'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tingkat pembayaran:</span>
                                <span class="font-bold {{ $payment['collection_rate'] >= 80 ? 'text-green-600' : ($payment['collection_rate'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $payment['collection_rate'] }}%
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <x-heroicon-o-banknotes class="w-12 h-12 mx-auto mb-3 opacity-50" />
                    <p>Belum ada data pembayaran untuk periode ini</p>
                </div>
            @endif
        </div>

        <!-- Income/Expense Summary -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Income Summary -->
            <div class="summary-section">
                <div class="section-title">
                    <x-heroicon-o-arrow-trending-up class="w-5 h-5 text-green-600" />
                    Pemasukan
                </div>
                
                @php $incomeData = $summary->get('income', collect()); @endphp
                
                @if($incomeData->count() > 0)
                    <div class="space-y-3">
                        @foreach($incomeData as $item)
                        <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                            <div>
                                <div class="font-medium text-green-900">{{ $item->category }}</div>
                                <div class="text-sm text-green-700">{{ $item->transaction_count }} transaksi</div>
                            </div>
                            <div class="font-bold text-green-900">
                                Rp {{ number_format($item->total_amount, 0, ',', '.') }}
                            </div>
                        </div>
                        @endforeach
                        <div class="border-t pt-3">
                            <div class="flex justify-between items-center font-bold text-green-900">
                                <span>Total Pemasukan:</span>
                                <span>Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <x-heroicon-o-arrow-trending-up class="w-12 h-12 mx-auto mb-3 opacity-50" />
                        <p>Belum ada data pemasukan</p>
                    </div>
                @endif
            </div>

            <!-- Expense Summary -->
            <div class="summary-section">
                <div class="section-title">
                    <x-heroicon-o-arrow-trending-down class="w-5 h-5 text-red-600" />
                    Pengeluaran
                </div>
                
                @php $expenseData = $summary->get('expense', collect()); @endphp
                
                @if($expenseData->count() > 0)
                    <div class="space-y-3">
                        @foreach($expenseData as $item)
                        <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                            <div>
                                <div class="font-medium text-red-900">{{ $item->category }}</div>
                                <div class="text-sm text-red-700">{{ $item->transaction_count }} transaksi</div>
                            </div>
                            <div class="font-bold text-red-900">
                                Rp {{ number_format($item->total_amount, 0, ',', '.') }}
                            </div>
                        </div>
                        @endforeach
                        <div class="border-t pt-3">
                            <div class="flex justify-between items-center font-bold text-red-900">
                                <span>Total Pengeluaran:</span>
                                <span>Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <x-heroicon-o-arrow-trending-down class="w-12 h-12 mx-auto mb-3 opacity-50" />
                        <p>Belum ada data pengeluaran</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Monthly Comparison (if yearly view) -->
        @if(!$this->selectedMonth)
        <div class="summary-section">
            <div class="section-title">
                <x-heroicon-o-chart-bar class="w-5 h-5 text-blue-600" />
                Perbandingan Bulanan {{ $this->selectedYear }}
            </div>
            
            @php $monthlyData = $this->getMonthlyComparison(); @endphp
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemasukan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengeluaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($monthlyData as $data)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $data['month'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                Rp {{ number_format($data['income'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                Rp {{ number_format($data['expense'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $data['balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                Rp {{ number_format($data['balance'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Info Section -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start gap-3">
                <x-heroicon-o-information-circle class="w-6 h-6 text-blue-600 mt-1 flex-shrink-0" />
                <div>
                    <h3 class="font-semibold text-blue-900 mb-2">Catatan Laporan:</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Data keuangan diperbarui secara real-time berdasarkan transaksi yang telah diverifikasi</li>
                        <li>• Tingkat pembayaran dihitung berdasarkan jumlah keluarga yang telah membayar iuran bulan berjalan</li>
                        <li>• Saldo bersih merupakan selisih antara total pemasukan dan pengeluaran</li>
                        <li>• Laporan dapat didownload dalam format PDF untuk keperluan dokumentasi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
