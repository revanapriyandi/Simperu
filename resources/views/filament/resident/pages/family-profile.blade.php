<x-filament-panels::page>
    @push('styles')
        <link href="{{ asset('css/resident-dashboard.css') }}" rel="stylesheet">
    @endpush

    <div class="space-y-8">
        @if ($family = $this->getFamily())
            <!-- Family Overview Card -->
            <div class="relative rounded-lg bg-primary-500 p-6 shadow-xl overflow-hidden ">
                <div class="flex items-start gap-6">
                    <div class="w-14 h-14 bg-white/20 rounded-lg flex items-center justify-center">
                        <x-heroicon-o-home-modern class="h-8 w-8 text-white" />
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-white mb-1">
                            Keluarga {{ $family->head_of_family }}
                        </h2>
                        <p class="text-white/80 text-sm mb-2">
                            Blok {{ $family->house_block }} • Villa Windaro Permai
                        </p>
                        <p class="text-white/90 text-base leading-relaxed max-w-xl">
                            Data lengkap anggota keluarga yang terdaftar di sistem perumahan Villa Windaro Permai.
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-white/20">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-white">{{ $family->family_members_count }}</div>
                        <div class="text-white/80 text-xs uppercase tracking-wide">Anggota</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-white">{{ $family->house_block }}</div>
                        <div class="text-white/80 text-xs uppercase tracking-wide">Blok Rumah</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-white">
                            @switch($family->house_status)
                                @case('owner')
                                    Milik
                                @break

                                @case('tenant')
                                    Sewa
                                @break

                                @default
                                    Keluarga
                            @endswitch
                        </div>
                        <div class="text-white/80 text-xs uppercase tracking-wide">Status</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-white">{{ $family->created_at->format('Y') }}</div>
                        <div class="text-white/80 text-xs uppercase tracking-wide">Terdaftar</div>
                    </div>
                </div>
            </div>

            <!-- Family Information Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Basic Info -->
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-lg p-6 shadow hover:shadow-lg transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <x-heroicon-o-identification class="w-5 h-5 text-blue-600" />
                        <span class="text-lg font-bold">Informasi Dasar</span>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">Nomor KK</div>
                            <div class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                {{ $family->kk_number }}
                            </div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">Kepala Keluarga</div>
                            <div class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                {{ $family->head_of_family }}
                            </div>
                        </div>
                        @if ($family->wife_name)
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">Istri</div>
                                <div class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $family->wife_name }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Contact Info -->
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-lg p-6 shadow hover:shadow-lg transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <x-heroicon-o-phone class="w-5 h-5 text-green-600" />
                        <span class="text-lg font-bold">Kontak</span>
                    </div>
                    <div class="space-y-3">
                        @foreach (['phone_1', 'phone_2'] as $index => $phoneField)
                            @if ($family->{$phoneField})
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">Telepon
                                        {{ $index + 1 }}</div>
                                    <a href="tel:{{ $family->{$phoneField} }}"
                                        class="text-base font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                                        {{ $family->{$phoneField} }}
                                    </a>
                                </div>
                            @endif
                        @endforeach
                        @if (!$family->phone_1 && !$family->phone_2)
                            <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                                <x-heroicon-o-phone class="w-7 h-7 mx-auto mb-2 opacity-50" />
                                <span class="text-xs">Tidak ada data kontak</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Vehicle Info -->
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-lg p-6 shadow hover:shadow-lg transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <x-heroicon-o-truck class="w-5 h-5 text-purple-600" />
                        <span class="text-lg font-bold">Kendaraan</span>
                    </div>
                    <div class="space-y-3">
                        @php $hasVehicles = $family->license_plate_1 || $family->license_plate_2; @endphp
                        @if ($hasVehicles)
                            @foreach (['license_plate_1', 'license_plate_2'] as $index => $plateField)
                                @if ($family->{$plateField})
                                    <div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">Kendaraan
                                            {{ $index + 1 }}</div>
                                        <div class="text-base font-semibold text-purple-700 dark:text-purple-300">
                                            {{ $family->{$plateField} }}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                                <x-heroicon-o-truck class="w-7 h-7 mx-auto mb-2 opacity-50" />
                                <span class="text-xs">Tidak ada data kendaraan</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Family Members -->
            @php
                $familyMembers = $this->getFamilyMembers();
                $memberCount = $familyMembers->count();
            @endphp
            <div
                class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-lg p-6 shadow space-y-6">
                <div class="flex items-center gap-3 mb-4">
                    <x-heroicon-o-users class="w-5 h-5 text-indigo-600" />
                    <span class="text-lg font-bold">Anggota Keluarga ({{ $memberCount }} orang)</span>
                </div>
                @if ($memberCount > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($familyMembers as $member)
                            <div
                                class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl p-5 hover:shadow-lg transition-all flex gap-4">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <x-heroicon-o-user class="w-6 h-6 text-white" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 truncate mb-1">
                                        {{ $member->name }}</h4>
                                    <div class="flex items-center gap-2 mb-2">
                                        @php
                                            $relationshipClasses = [
                                                'kepala_keluarga' =>
                                                    'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                'istri' =>
                                                    'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200',
                                                'anak' =>
                                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                'default' =>
                                                    'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                                            ];
                                            $relationshipClass =
                                                $relationshipClasses[$member->relationship] ??
                                                $relationshipClasses['default'];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $relationshipClass }}">
                                            {{ ucwords(str_replace('_', ' ', $member->relationship)) }}
                                        </span>
                                    </div>
                                    <div class="space-y-1 text-xs">
                                        @if ($member->nik)
                                            <div class="text-gray-500 dark:text-gray-400"><span
                                                    class="font-medium">NIK:</span> {{ $member->nik }}</div>
                                        @endif
                                        @if ($member->birth_date)
                                            <div class="text-gray-500 dark:text-gray-400">
                                                <span class="font-medium">Lahir:</span>
                                                {{ $member->birth_date->format('d M Y') }}
                                                @if ($member->age)
                                                    ({{ $member->age }} tahun)
                                                @endif
                                            </div>
                                        @endif
                                        @if ($member->gender)
                                            <div class="text-gray-500 dark:text-gray-400">
                                                <span class="font-medium">Jenis Kelamin:</span>
                                                {{ $member->gender === 'laki-laki' ? '♂ Laki-laki' : '♀ Perempuan' }}
                                            </div>
                                        @endif
                                        @if ($member->occupation)
                                            <div class="text-gray-500 dark:text-gray-400"><span
                                                    class="font-medium">Pekerjaan:</span> {{ $member->occupation }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                        <x-heroicon-o-users class="w-12 h-12 mx-auto mb-3 opacity-50" />
                        <div class="text-base font-medium mb-1">Belum Ada Data Anggota Keluarga</div>
                        <div class="text-sm mb-3">Data anggota keluarga akan ditampilkan setelah diinput oleh admin
                        </div>
                        <div
                            class="bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800 rounded-lg p-4 max-w-md mx-auto">
                            <div class="flex items-start gap-3">
                                <x-heroicon-o-information-circle
                                    class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" />
                                <div class="text-left">
                                    <div class="font-medium text-blue-900 dark:text-blue-100 mb-1">Cara Menambah Data:
                                    </div>
                                    <div class="text-xs text-blue-800 dark:text-blue-200">
                                        Hubungi pengurus untuk menambahkan data anggota keluarga atau lengkapi saat
                                        pendaftaran.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Account Connection -->
            @if ($family->user)
                <div
                    class="bg-white dark:bg-gray-800 border border-emerald-200 dark:border-emerald-800 rounded-lg p-6 shadow">
                    <div class="flex items-center gap-3 mb-3">
                        <x-heroicon-o-link class="w-5 h-5 text-emerald-600" />
                        <span class="text-lg font-bold">Akun Terhubung</span>
                    </div>
                    <div
                        class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900 rounded-full flex items-center justify-center">
                                <x-heroicon-o-check-circle class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <div>
                                <div class="font-medium text-emerald-900 dark:text-emerald-100">
                                    {{ $family->user->name }}</div>
                                <div class="text-sm text-emerald-700 dark:text-emerald-300">{{ $family->user->email }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <!-- No Family Data -->
            <div class="text-center py-16">
                <x-heroicon-o-exclamation-triangle class="w-16 h-16 mx-auto mb-4 text-gray-400" />
                <div class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Data Keluarga Tidak Ditemukan
                </div>
                <div class="text-gray-500 dark:text-gray-400 mb-6">Akun Anda belum terhubung dengan data keluarga.
                </div>
                <div
                    class="bg-yellow-50 dark:bg-yellow-950/30 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 max-w-md mx-auto">
                    <div class="flex items-start gap-3">
                        <x-heroicon-o-light-bulb class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5" />
                        <div class="text-left">
                            <div class="font-medium text-yellow-900 dark:text-yellow-100 mb-1">Langkah Selanjutnya:
                            </div>
                            <div class="text-xs text-yellow-800 dark:text-yellow-200">
                                Hubungi pengurus untuk menghubungkan akun Anda dengan data keluarga yang sudah ada atau
                                daftarkan data keluarga baru.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
