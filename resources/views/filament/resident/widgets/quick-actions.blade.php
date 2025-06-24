<x-filament-widgets::widget>
    <x-filament::section>
        {{-- <x-slot name="heading">
            <div class="flex items-center gap-x-3">
                <div
                    class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-md">
                    <x-heroicon-m-bolt class="h-6 w-6 text-white" />
                </div>
            </div>
        </x-slot>

        <!-- ALERT SECTION -->
        <div class="space-y-4 mb-4">
            @if ($totalOutstanding > 0)
                <div
                    class="bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 rounded-xl p-5 flex items-start gap-4">
                    <x-heroicon-o-exclamation-triangle
                        class="w-7 h-7 text-red-600 dark:text-red-400 mt-1 flex-shrink-0" />
                    <div class="flex-1">
                        <h3 class="font-semibold text-red-900 dark:text-red-100 mb-1">Tunggakan Pembayaran</h3>
                        <p class="text-sm text-red-800 dark:text-red-200 mb-2">
                            Anda memiliki tunggakan sebesar <strong>Rp
                                {{ number_format($totalOutstanding, 0, ',', '.') }}</strong> untuk
                            {{ count($outstandingPayments) }} iuran.
                        </p>
                        <div class="space-y-2">
                            @foreach ($outstandingPayments as $outstanding)
                                <div
                                    class="flex justify-between items-center bg-white dark:bg-red-900/20 rounded-lg p-3">
                                    <div>
                                        <div class="font-medium text-red-900 dark:text-red-100">
                                            {{ $outstanding['fee_type'] }}</div>
                                        <div class="text-sm text-red-700 dark:text-red-300">
                                            Jatuh tempo:
                                            {{ \Carbon\Carbon::parse($outstanding['due_date'])->format('d M Y') }}
                                        </div>
                                    </div>
                                    <div class="font-bold text-red-900 dark:text-red-100">
                                        Rp {{ number_format($outstanding['amount'], 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('filament.resident.resources.payment-submissions.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition">
                                <x-heroicon-o-credit-card class="w-4 h-4 mr-2" />Upload Bukti Pembayaran
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            @if (!$hasCompleteProfile)
                <div
                    class="bg-yellow-50 dark:bg-yellow-950/30 border border-yellow-200 dark:border-yellow-800 rounded-xl p-5 flex items-start gap-4">
                    <x-heroicon-o-exclamation-triangle
                        class="w-7 h-7 text-yellow-600 dark:text-yellow-400 mt-1 flex-shrink-0" />
                    <div class="flex-1">
                        <h3 class="font-semibold text-yellow-900 dark:text-yellow-100 mb-1">Data Keluarga Belum Lengkap
                        </h3>
                        <p class="text-sm text-yellow-800 dark:text-yellow-200 mb-2">Lengkapi data keluarga Anda untuk
                            akses penuh layanan perumahan.</p>
                        <a href="{{ \App\Filament\Resident\Pages\FamilyDataManagement\CompleteFamilyData::getUrl() }}"
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition">
                            <x-heroicon-o-user-plus class="w-4 h-4 mr-2" />Lengkapi Data Keluarga
                        </a>
                    </div>
                </div>
            @endif
        </div> --}}

        <!-- WELCOME CARD -->
        <div class="relative rounded-lg bg-primary-500 p-4 shadow-xl text-white mb-8 overflow-hidden">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-3">
                <div class="flex-shrink-0 w-14 h-14 bg-white/20 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-home-modern class="h-8 w-8 text-white" />
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold mb-1">Selamat datang, {{ $user->name }}! 👋</h2>
                    <p class="text-white/80 text-sm mb-2">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
                    <p class="text-white/90 text-base leading-relaxed max-w-lg">
                        Sistem Informasi Manajemen Perumahan Villa Windaro Permai. Kelola surat pengaduan, pembayaran,
                        dan informasi perumahan dengan mudah dan efisien.
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8 pt-6 border-t border-white/20">
                <div class="text-center">
                    <div class="text-2xl font-bold">{{ $approvedLetters }}</div>
                    <div class="text-white/80 text-xs uppercase tracking-wide">Surat Disetujui</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold">{{ $verifiedPayments }}</div>
                    <div class="text-white/80 text-xs uppercase tracking-wide">Bayar Verified</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold">{{ $familyMembersCount }}</div>
                    <div class="text-white/80 text-xs uppercase tracking-wide">Anggota Keluarga</div>
                </div>
                <div class="text-center">
                    @if ($totalOutstanding > 0)
                        <div class="text-2xl font-bold text-yellow-300">{{ count($outstandingPayments) }}</div>
                        <div class="text-yellow-200 text-xs uppercase tracking-wide">Tunggakan</div>
                    @else
                        <div class="text-2xl font-bold text-green-300">✓</div>
                        <div class="text-green-200 text-xs uppercase tracking-wide">Lunas</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- TIPS -->
        <div class="mt-8 p-4 bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800 rounded-xl">
            <div class="flex items-start gap-3">
                <x-heroicon-o-light-bulb class="h-6 w-6 text-blue-600 dark:text-blue-400 mt-1 flex-shrink-0" />
                <div>
                    <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">💡 Tips & Informasi Penting</h4>
                    <ul class="list-disc space-y-1 pl-5 text-sm text-blue-800 dark:text-blue-200">
                        <li><b>Pengaduan:</b> Jelaskan masalah secara detail dan lampirkan bukti jika perlu.</li>
                        <li><b>Pembayaran:</b> Upload bukti yang jelas, sertakan info transfer dan tunggu verifikasi
                            admin.</li>
                        <li><b>Penting:</b> Iuran dibayar tgl 1-10, surat disetujui bisa diunduh digital, bantuan:
                            081-234-567-890.</li>
                    </ul>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
