<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Welcome Message -->
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg p-6 text-white">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-bold">Selamat Datang, {{ auth()->user()->name }}!</h2>
                    <p class="text-green-100 mt-1">Portal Warga SIMPERU - Kelola administrasi rumah Anda dengan mudah
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('filament.resident.resources.payment-submissions.create') }}"
                class="block p-6 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Ajukan Pembayaran</h3>
                        <p class="text-sm text-gray-600">Submit pembayaran iuran bulanan</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('filament.resident.resources.complaint-letters.create') }}"
                class="block p-6 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Buat Pengaduan</h3>
                        <p class="text-sm text-gray-600">Laporkan keluhan atau masalah</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('filament.resident.resources.announcements.index') }}"
                class="block p-6 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Lihat Pengumuman</h3>
                        <p class="text-sm text-gray-600">Informasi terbaru dari pengurus</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Widgets -->
        <div class="space-y-6">
            @foreach ($this->getWidgets() as $widget)
                @livewire($widget)
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
