<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                <span>Overview Sistem</span>
            </div>
        </x-slot>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Statistik User -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                <h4 class="text-sm font-semibold text-blue-800 mb-3 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                        </path>
                    </svg>
                    Statistik User
                </h4>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-blue-700">Total User:</span>
                        <span class="font-semibold text-blue-900">{{ $systemInfo['total_users'] }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-blue-700">Admin:</span>
                        <span class="font-semibold text-blue-900">{{ $systemInfo['admin_users'] }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-blue-700">Warga:</span>
                        <span class="font-semibold text-blue-900">{{ $systemInfo['resident_users'] }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-blue-700">User Aktif:</span>
                        <span class="font-semibold text-blue-900">{{ $systemInfo['active_users'] }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-blue-700">Email Terverifikasi:</span>
                        <span class="font-semibold text-blue-900">{{ $systemInfo['verified_users'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Statistik Konten -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                <h4 class="text-sm font-semibold text-green-800 mb-3 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                        </path>
                    </svg>
                    Statistik Konten
                </h4>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-green-700">Total Pengaduan:</span>
                        <span class="font-semibold text-green-900">{{ $contentInfo['total_complaints'] }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-green-700">Pending:</span>
                        <span class="font-semibold text-green-900">{{ $contentInfo['pending_complaints'] }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-green-700">Disetujui:</span>
                        <span class="font-semibold text-green-900">{{ $contentInfo['approved_complaints'] }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-green-700">Total Pengumuman:</span>
                        <span class="font-semibold text-green-900">{{ $contentInfo['total_announcements'] }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-green-700">Pengumuman Aktif:</span>
                        <span class="font-semibold text-green-900">{{ $contentInfo['active_announcements'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Aktivitas Hari Ini -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                <h4 class="text-sm font-semibold text-purple-800 mb-3 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Aktivitas Hari Ini
                </h4>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-purple-700">User Baru:</span>
                        <span class="font-semibold text-purple-900">{{ $recentActivity['new_users_today'] }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-purple-700">Pengaduan Baru:</span>
                        <span
                            class="font-semibold text-purple-900">{{ $recentActivity['new_complaints_today'] }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-purple-700">Pengumuman Baru:</span>
                        <span
                            class="font-semibold text-purple-900">{{ $recentActivity['new_announcements_today'] }}</span>
                    </div>
                    <div class="pt-2 border-t border-purple-200">
                        <p class="text-xs text-purple-600">
                            Terakhir update: {{ now()->format('H:i:s') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
