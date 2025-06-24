<x-filament-panels::page>
    @push('styles')
        <link href="{{ asset('css/resident-dashboard.css') }}" rel="stylesheet">
        <style>
            .financial-header {
                background: linear-gradient(135deg, #059669 0%, #10b981 100%);
                border-radius: 12px;
                padding: 2rem;
                margin-bottom: 2rem;
                color: white;
            }
            
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
            
            .report-section {
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
            
            .payment-item {
                display: flex;
                justify-content: between;
                align-items: center;
                padding: 1rem;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                margin-bottom: 0.5rem;
            }
            
            .payment-item:hover {
                background: #f9fafb;
            }
            
            .status-badge {
                padding: 0.25rem 0.75rem;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 500;
            }
            
            .status-verified { background: #dcfce7; color: #166534; }
            .status-pending { background: #fef3c7; color: #92400e; }
            .status-rejected { background: #fee2e2; color: #991b1b; }
            
            .chart-container {
                height: 300px;
                margin: 1rem 0;
            }
        </style>
    @endpush

    <div class="max-w-6xl mx-auto">
        <!-- Header Section -->
        <div class="financial-header">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <x-heroicon-o-chart-bar class="h-6 w-6 text-white" />
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">
                                Laporan Keuangan
                            </h1>
                            <p class="text-white/80 text-sm">
                                @if($this->selectedMonth)
                                    Bulan {{ $this->getMonthName($this->selectedMonth) }} {{ $this->selectedYear }}
                                @else
                                    Tahun {{ $this->selectedYear }}
                                @endif
                                • {{ Auth::user()->family?->head_of_family ?? 'Keluarga' }}
                            </p>
                        </div>
                    </div>
                    <p class="text-white/90 leading-relaxed max-w-2xl">
                        Laporan pembayaran iuran dan administrasi keluarga di Villa Windaro Permai. 
                        Pantau status pembayaran dan riwayat transaksi Anda.
                    </p>
                </div>
            </div>

            <!-- Financial Stats -->
            <div class="stats-grid">
                @php
                    $summary = $this->getPaymentSummary();
                    $totalPaid = $summary->sum('total_paid');
                    $totalPending = $summary->sum('total_pending');
                    $outstanding = $this->getOutstandingPayments();
                @endphp
                
                <div class="stat-card">
                    <div class="stat-value">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
                    <div class="stat-label">Total Terbayar</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">Rp {{ number_format($totalPending, 0, ',', '.') }}</div>
                    <div class="stat-label">Menunggu Verifikasi</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $outstanding->count() }}</div>
                    <div class="stat-label">Tunggakan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $this->getPaymentHistory()->count() }}</div>
                    <div class="stat-label">Total Transaksi</div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="report-section">
            {{ $this->form }}
        </div>

        <!-- Outstanding Payments Alert -->
        @if($outstanding->count() > 0)
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-600 mt-1 flex-shrink-0" />
                <div class="flex-1">
                    <h3 class="font-semibold text-red-900 mb-2">Tunggakan Pembayaran</h3>
                    <div class="space-y-2">
                        @foreach($outstanding as $item)
                        <div class="flex justify-between items-center bg-white rounded-lg p-3">
                            <div>
                                <div class="font-medium text-red-900">{{ $item['fee_type'] }}</div>
                                <div class="text-sm text-red-700">
                                    Jatuh tempo: {{ \Carbon\Carbon::parse($item['due_date'])->format('d M Y') }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-red-900">Rp {{ number_format($item['amount'], 0, ',', '.') }}</div>
                                @if($item['days_overdue'] > 0)
                                <div class="text-xs text-red-600">{{ $item['days_overdue'] }} hari terlambat</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Payment Summary -->
        <div class="report-section">
            <div class="section-title">
                <x-heroicon-o-chart-pie class="w-5 h-5 text-blue-600" />
                Ringkasan Pembayaran
            </div>
            
            @if($summary->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($summary as $item)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-3">{{ $item['fee_type'] }}</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Iuran per bulan:</span>
                                <span class="font-medium">Rp {{ number_format($item['amount'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total terbayar:</span>
                                <span class="font-medium text-green-600">Rp {{ number_format($item['total_paid'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Menunggu verifikasi:</span>
                                <span class="font-medium text-yellow-600">Rp {{ number_format($item['total_pending'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Pembayaran verified:</span>
                                <span class="font-medium">{{ $item['verified_count'] }}/{{ $item['payments_count'] }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <x-heroicon-o-chart-pie class="w-12 h-12 mx-auto mb-3 opacity-50" />
                    <p>Belum ada data pembayaran untuk periode ini</p>
                </div>
            @endif
        </div>

        <!-- Payment Chart -->
        @if(!$this->selectedMonth)
        <div class="report-section">
            <div class="section-title">
                <x-heroicon-o-chart-bar class="w-5 h-5 text-green-600" />
                Grafik Pembayaran Tahunan {{ $this->selectedYear }}
            </div>
            
            <div class="chart-container">
                @php
                    $chartData = $this->getMonthlyPaymentChart();
                @endphp
                
                <canvas id="paymentChart" width="400" height="200"></canvas>
            </div>
        </div>
        @endif

        <!-- Payment History -->
        <div class="report-section">
            <div class="section-title">
                <x-heroicon-o-clock class="w-5 h-5 text-purple-600" />
                Riwayat Pembayaran
            </div>
            
            @php $payments = $this->getPaymentHistory(); @endphp
            
            @if($payments->count() > 0)
                <div class="space-y-2">
                    @foreach($payments as $payment)
                    <div class="payment-item">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $payment->feeType->name }}</div>
                                    <div class="text-sm text-gray-600">
                                        {{ $payment->payment_date->format('d M Y') }} • 
                                        {{ $payment->payment_method ?? 'Transfer Bank' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                            <span class="status-badge status-{{ $payment->status }}">
                                {{ match($payment->status) {
                                    'verified' => 'Terverifikasi',
                                    'pending' => 'Menunggu',
                                    'rejected' => 'Ditolak',
                                    default => $payment->status
                                } }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <x-heroicon-o-clock class="w-12 h-12 mx-auto mb-3 opacity-50" />
                    <p>Belum ada riwayat pembayaran untuk periode ini</p>
                </div>
            @endif
        </div>

        <!-- Info Section -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start gap-3">
                <x-heroicon-o-information-circle class="w-6 h-6 text-blue-600 mt-1 flex-shrink-0" />
                <div>
                    <h3 class="font-semibold text-blue-900 mb-2">Informasi Pembayaran:</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Pembayaran iuran dapat dilakukan melalui transfer bank atau tunai ke pengurus</li>
                        <li>• Upload bukti pembayaran melalui halaman "Upload Pembayaran" untuk verifikasi</li>
                        <li>• Status "Menunggu" berarti bukti pembayaran sedang dalam proses verifikasi</li>
                        <li>• Hubungi pengurus jika ada pertanyaan terkait pembayaran atau tunggakan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        @if(!$this->selectedMonth)
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('paymentChart');
            if (ctx) {
                const chartData = @json($chartData ?? []);
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartData.map(item => item.month),
                        datasets: [{
                            label: 'Pembayaran (Rp)',
                            data: chartData.map(item => item.amount),
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
        @endif
    </script>
    @endpush
</x-filament-panels::page>
