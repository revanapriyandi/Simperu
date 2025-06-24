<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-x-3">
                <div class="flex items-center justify-center w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg">
                    <x-heroicon-m-bolt class="h-5 w-5 text-white" />
                </div>
                <span class="text-lg font-semibold">Aksi Cepat</span>
            </div>
        </x-slot>
        
        <x-slot name="description">
            Akses cepat ke fitur-fitur utama sistem informasi perumahan
        </x-slot>

        <!-- Welcome Message Card -->
        <div class="resident-welcome-card mb-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <x-heroicon-o-home-modern class="h-6 w-6 text-white" />
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">
                                Selamat datang, {{ $user->name }}! 👋
                            </h2>
                            <p class="text-white/80 text-sm">
                                {{ now()->isoFormat('dddd, D MMMM Y') }}
                            </p>
                        </div>
                    </div>
                    <p class="text-white/90 text-sm leading-relaxed max-w-lg">
                        Sistem Informasi Manajemen Perumahan Villa Windaro Permai. 
                        Kelola surat pengaduan, pembayaran, dan informasi perumahan dengan mudah dan efisien.
                    </p>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-6 border-t border-white/20">
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">{{ $pendingLetters }}</div>
                    <div class="text-white/80 text-xs uppercase tracking-wide">Surat Pending</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">{{ $pendingPayments }}</div>
                    <div class="text-white/80 text-xs uppercase tracking-wide">Bayar Pending</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">{{ $unreadAnnouncements }}</div>
                    <div class="text-white/80 text-xs uppercase tracking-wide">Info Baru</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">{{ now()->format('d') }}</div>
                    <div class="text-white/80 text-xs uppercase tracking-wide">{{ now()->format('M Y') }}</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Grid -->
        <div class="resident-quick-actions">
            <!-- Buat Surat Pengaduan -->
            <a href="{{ route('filament.resident.resources.complaint-letters.create') }}" 
               class="resident-action-card block group">
                <div class="resident-action-icon group-hover:scale-110 transition-transform duration-300">
                    <x-heroicon-o-document-plus class="h-6 w-6" />
                </div>
                <h3 class="resident-action-title">
                    Ajukan Surat Pengaduan
                </h3>
                <p class="resident-action-description">
                    Buat pengaduan baru untuk masalah di lingkungan perumahan seperti kebersihan, keamanan, atau fasilitas.
                </p>
                @if($pendingLetters > 0)
                    <div class="mt-3 flex items-center gap-2">
                        <div class="status-indicator status-pending">
                            <x-heroicon-m-clock class="w-3 h-3" />
                            {{ $pendingLetters }} menunggu persetujuan
                        </div>
                    </div>
                @else
                    <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                        ✨ Tidak ada surat pending
                    </div>
                @endif
            </a>

            <!-- Upload Bukti Pembayaran -->
            <a href="{{ route('filament.resident.resources.payment-submissions.create') }}" 
               class="resident-action-card block group">
                <div class="resident-action-icon group-hover:scale-110 transition-transform duration-300">
                    <x-heroicon-o-credit-card class="h-6 w-6" />
                </div>
                <h3 class="resident-action-title">
                    Upload Bukti Bayar
                </h3>
                <p class="resident-action-description">
                    Upload bukti pembayaran iuran bulanan, dana sosial, atau pembayaran lainnya untuk verifikasi.
                </p>
                @if($pendingPayments > 0)
                    <div class="mt-3 flex items-center gap-2">
                        <div class="status-indicator status-pending">
                            <x-heroicon-m-clock class="w-3 h-3" />
                            {{ $pendingPayments }} menunggu verifikasi
                        </div>
                    </div>
                @else
                    <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                        ✅ Semua pembayaran terverifikasi
                    </div>
                @endif
            </a>

            <!-- Lihat Pengumuman -->
            <a href="{{ route('filament.resident.resources.announcements.index') }}" 
               class="resident-action-card block group">
                <div class="resident-action-icon group-hover:scale-110 transition-transform duration-300">
                    <x-heroicon-o-megaphone class="h-6 w-6" />
                </div>
                <h3 class="resident-action-title">
                    Pengumuman
                </h3>
                <p class="resident-action-description">
                    Lihat pengumuman dan informasi terbaru dari pengurus perumahan mengenai kegiatan dan kebijakan.
                </p>
                @if($unreadAnnouncements > 0)
                    <div class="mt-3 flex items-center gap-2">
                        <div class="status-indicator status-in-progress">
                            <x-heroicon-m-bell class="w-3 h-3" />
                            {{ $unreadAnnouncements }} pengumuman baru
                        </div>
                    </div>
                @else
                    <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                        📰 Semua pengumuman sudah dibaca
                    </div>
                @endif
            </a>

            <!-- Data Keluarga -->
            <a href="{{ route('filament.resident.resources.families.index') }}" 
               class="resident-action-card block group">
                <div class="resident-action-icon group-hover:scale-110 transition-transform duration-300">
                    <x-heroicon-o-user-group class="h-6 w-6" />
                </div>
                <h3 class="resident-action-title">
                    Data Keluarga
                </h3>
                <p class="resident-action-description">
                    Kelola data keluarga, anggota rumah tangga, dan informasi kontak untuk keperluan administrasi.
                </p>
                <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                    👨‍👩‍👧‍👦 Kelola data keluarga Anda
                </div>
            </a>

            <!-- Laporan Keuangan -->
            <a href="#" 
               class="resident-action-card block group opacity-75 cursor-not-allowed">
                <div class="resident-action-icon group-hover:scale-110 transition-transform duration-300">
                    <x-heroicon-o-chart-bar class="h-6 w-6" />
                </div>
                <h3 class="resident-action-title">
                    Laporan Keuangan
                </h3>
                <p class="resident-action-description">
                    Lihat laporan keuangan perumahan, grafik pemasukan dan pengeluaran bulanan atau tahunan.
                </p>
                <div class="mt-3 text-xs text-amber-600 dark:text-amber-400">
                    🚧 Segera hadir
                </div>
            </a>

            <!-- Profile Settings -->
            <a href="#" 
               class="resident-action-card block group opacity-75 cursor-not-allowed">
                <div class="resident-action-icon group-hover:scale-110 transition-transform duration-300">
                    <x-heroicon-o-cog-6-tooth class="h-6 w-6" />
                </div>
                <h3 class="resident-action-title">
                    Pengaturan Profil
                </h3>
                <p class="resident-action-description">
                    Edit profil, ubah password, dan kelola preferensi notifikasi untuk pengalaman yang lebih personal.
                </p>
                <div class="mt-3 text-xs text-amber-600 dark:text-amber-400">
                    🚧 Segera hadir
                </div>
            </a>
        </div>

        <!-- Tips Section -->
        <div class="mt-8 p-4 bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800 rounded-lg">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <x-heroicon-o-light-bulb class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                </div>
                <div>
                    <h4 class="font-medium text-blue-900 dark:text-blue-100 mb-2">💡 Tips Penggunaan</h4>
                    <div class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                        <p>• <strong>Surat Pengaduan:</strong> Berikan deskripsi yang jelas dan lampirkan foto jika diperlukan</p>
                        <p>• <strong>Bukti Pembayaran:</strong> Upload dalam format JPG/PNG dengan kualitas yang jelas</p>
                        <p>• <strong>Notifikasi:</strong> Aktifkan notifikasi untuk mendapat update status surat Anda</p>
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
