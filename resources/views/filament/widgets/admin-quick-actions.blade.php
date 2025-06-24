<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Dashboard Pengurus - Aksi Cepat
        </x-slot>

        <!-- Financial Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 border border-emerald-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-emerald-800">Pemasukan Bulan Ini</h3>
                        <p class="text-xl font-bold text-emerald-900">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-2 bg-emerald-200 rounded-full">
                        <x-heroicon-o-arrow-trending-up class="w-5 h-5 text-emerald-600" />
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-red-800">Pengeluaran Bulan Ini</h3>
                        <p class="text-xl font-bold text-red-900">Rp {{ number_format($monthlyExpense, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-2 bg-red-200 rounded-full">
                        <x-heroicon-o-arrow-trending-down class="w-5 h-5 text-red-600" />
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-blue-800">Saldo Bersih</h3>
                        <p class="text-xl font-bold {{ ($monthlyIncome - $monthlyExpense) >= 0 ? 'text-blue-900' : 'text-red-900' }}">
                            Rp {{ number_format($monthlyIncome - $monthlyExpense, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-2 bg-blue-200 rounded-full">
                        <x-heroicon-o-banknotes class="w-5 h-5 text-blue-600" />
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-orange-50 to-orange-100 border border-orange-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-orange-800">Tunggakan Iuran</h3>
                        <p class="text-xl font-bold text-orange-900">Rp {{ number_format($totalOutstanding, 0, ',', '.') }}</p>
                        <p class="text-xs text-orange-700">{{ $outstandingFamilies }} keluarga</p>
                    </div>
                    <div class="p-2 bg-orange-200 rounded-full">
                        <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-orange-600" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Pengaduan Pending -->
            <div class="bg-gradient-to-r from-orange-50 to-orange-100 border border-orange-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-orange-800">Surat Pengaduan</h3>
                        <p class="text-2xl font-bold text-orange-900">{{ $pendingComplaints }}</p>
                        <p class="text-xs text-orange-700">Menunggu persetujuan</p>
                    </div>
                    <div class="p-3 bg-orange-200 rounded-full">
                        <x-heroicon-o-document-text class="w-6 h-6 text-orange-600" />
                    </div>
                </div>
                <a href="/admin/complaint-letters"
                    class="inline-flex items-center mt-2 text-sm text-orange-600 hover:text-orange-800">
                    Proses Surat
                    <x-heroicon-o-arrow-right class="w-4 h-4 ml-1" />
                </a>
            </div>

            <!-- Pembayaran Pending -->
            <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-red-800">Bukti Pembayaran</h3>
                        <p class="text-2xl font-bold text-red-900">{{ $pendingPayments }}</p>
                        <p class="text-xs text-red-700">Perlu verifikasi</p>
                    </div>
                    <div class="p-3 bg-red-200 rounded-full">
                        <x-heroicon-o-credit-card class="w-6 h-6 text-red-600" />
                    </div>
                </div>
                <a href="/admin/payment-submissions"
                    class="inline-flex items-center mt-2 text-sm text-red-600 hover:text-red-800">
                    Verifikasi Pembayaran
                    <x-heroicon-o-arrow-right class="w-4 h-4 ml-1" />
                </a>
            </div>

            <!-- Data Keluarga -->
            <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-green-800">Data Warga</h3>
                        <p class="text-2xl font-bold text-green-900">{{ $totalFamilies }}</p>
                        <p class="text-xs text-green-700">Keluarga terdaftar</p>
                    </div>
                    <div class="p-3 bg-green-200 rounded-full">
                        <x-heroicon-o-home-modern class="w-6 h-6 text-green-600" />
                    </div>
                </div>
                <a href="/admin/families"
                    class="inline-flex items-center mt-2 text-sm text-green-600 hover:text-green-800">
                    Kelola Data Keluarga
                    <x-heroicon-o-arrow-right class="w-4 h-4 ml-1" />
                </a>
            </div>

            <!-- Laporan Keuangan -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-blue-800">Laporan Keuangan</h3>
                        <p class="text-2xl font-bold text-blue-900">📊</p>
                        <p class="text-xs text-blue-700">Ringkasan & analisis</p>
                    </div>
                    <div class="p-3 bg-blue-200 rounded-full">
                        <x-heroicon-o-chart-bar class="w-6 h-6 text-blue-600" />
                    </div>
                </div>
                <a href="/admin/financial-summary"
                    class="inline-flex items-center mt-2 text-sm text-blue-600 hover:text-blue-800">
                    Lihat Laporan
                    <x-heroicon-o-arrow-right class="w-4 h-4 ml-1" />
                </a>
            </div>
        </div>

        <!-- Additional Actions -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-4">
                <h4 class="font-medium text-purple-900 mb-2">🏠 Manajemen Perumahan</h4>
                <div class="space-y-2">
                    <a href="/admin/announcements" class="block text-sm text-purple-700 hover:text-purple-900">
                        • Kelola Pengumuman
                    </a>
                    <a href="/admin/activity-photos" class="block text-sm text-purple-700 hover:text-purple-900">
                        • Foto Kegiatan
                    </a>
                    <a href="/admin/fee-types" class="block text-sm text-purple-700 hover:text-purple-900">
                        • Jenis Iuran
                    </a>
                </div>
            </div>

            <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 border border-indigo-200 rounded-lg p-4">
                <h4 class="font-medium text-indigo-900 mb-2">💰 Keuangan & Iuran</h4>
                <div class="space-y-2">
                    <a href="/admin/financial-transactions" class="block text-sm text-indigo-700 hover:text-indigo-900">
                        • Transaksi Keuangan
                    </a>
                    <a href="/admin/payment-submissions" class="block text-sm text-indigo-700 hover:text-indigo-900">
                        • Pembayaran Warga
                    </a>
                    <a href="/admin/financial-summary" class="block text-sm text-indigo-700 hover:text-indigo-900">
                        • Laporan Bulanan
                    </a>
                </div>
            </div>

            <div class="bg-gradient-to-r from-teal-50 to-teal-100 border border-teal-200 rounded-lg p-4">
                <h4 class="font-medium text-teal-900 mb-2">📋 Administrasi</h4>
                <div class="space-y-2">
                    <a href="/admin/users" class="block text-sm text-teal-700 hover:text-teal-900">
                        • Kelola Pengguna
                    </a>
                    <a href="/admin/letter-categories" class="block text-sm text-teal-700 hover:text-teal-900">
                        • Kategori Surat
                    </a>
                    <a href="/admin/landing-page-settings" class="block text-sm text-teal-700 hover:text-teal-900">
                        • Pengaturan Website
                    </a>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
