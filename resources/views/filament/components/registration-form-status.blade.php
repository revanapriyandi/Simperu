@php
    $data = $this->form->getRawState();
    $isComplete = $this->isFormComplete();

    // Hitung persentase kelengkapan
    $requiredFields = [
        'name',
        'nik',
        'email',
        'phone',
        'kk_number',
        'family_name',
        'address',
        'house_status',
        'password',
        'password_confirmation',
    ];
    $filledFields = 0;

    foreach ($requiredFields as $field) {
        if (!empty($data[$field])) {
            $filledFields++;
        }
    }

    $progress = round(($filledFields / count($requiredFields)) * 100);

    // Periksa validasi format
    $hasValidNik = !empty($data['nik']) && preg_match('/^[0-9]{16}$/', $data['nik']);
    $hasValidKk = !empty($data['kk_number']) && preg_match('/^[0-9]{16}$/', $data['kk_number']);
    $hasValidEmail = !empty($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL);
    $hasValidPassword = !empty($data['password']) && strlen($data['password']) >= 8;
    $passwordsMatch = ($data['password'] ?? '') === ($data['password_confirmation'] ?? '');
@endphp

<div class="mt-6 space-y-4">
    <!-- Progress indicator -->
    <div class="bg-white border border-gray-200 rounded-xl p-4">
        <div class="flex items-center justify-between mb-3">
            <h4 class="text-sm font-semibold text-gray-900">Progress Pengisian Form</h4>
            <span class="text-sm font-medium text-gray-600">{{ $progress }}%</span>
        </div>

        <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-300"
                style="width: {{ $progress }}%"></div>
        </div>

        <div class="grid grid-cols-2 gap-2 text-xs">
            <!-- Field status indicators -->
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 rounded-full {{ !empty($data['name']) ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                <span class="text-gray-600">Nama Lengkap</span>
            </div>

            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 rounded-full {{ $hasValidNik ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                <span class="text-gray-600">NIK</span>
            </div>

            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 rounded-full {{ $hasValidEmail ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                <span class="text-gray-600">Email</span>
            </div>

            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 rounded-full {{ $hasValidKk ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                <span class="text-gray-600">Nomor KK</span>
            </div>

            <div class="flex items-center space-x-2">
                <div
                    class="w-2 h-2 rounded-full {{ !empty($data['head_of_family']) ? 'bg-green-500' : 'bg-gray-300' }}">
                </div>
                <span class="text-gray-600">Kepala Keluarga</span>
            </div>

            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 rounded-full {{ !empty($data['house_number']) ? 'bg-green-500' : 'bg-gray-300' }}">
                </div>
                <span class="text-gray-600">Nomor Rumah</span>
            </div>

            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 rounded-full {{ !empty($data['house_block']) ? 'bg-green-500' : 'bg-gray-300' }}">
                </div>
                <span class="text-gray-600">Blok/RT</span>
            </div>

            <div class="flex items-center space-x-2">
                <div
                    class="w-2 h-2 rounded-full {{ $hasValidPassword && $passwordsMatch ? 'bg-green-500' : 'bg-gray-300' }}">
                </div>
                <span class="text-gray-600">Kata Sandi</span>
            </div>
        </div>
    </div>

    <!-- Register button -->
    @if ($isComplete)
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4">
            <div class="text-center">
                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-green-900 mb-2">Form Siap Dikirim!</h3>
                <p class="text-sm text-green-700 mb-4">Semua data telah diisi dengan lengkap dan valid.</p>

                <button type="submit"
                    class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                    wire:loading.attr="disabled" wire:target="register">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                        </path>
                    </svg>
                    <span wire:loading.remove wire:target="register">Daftar Sekarang</span>
                    <span wire:loading wire:target="register">Memproses...</span>
                </button>
            </div>
        </div>
    @else
        <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 rounded-xl p-4">
            <div class="text-center">
                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-yellow-900 mb-2">Lengkapi Data Terlebih Dahulu</h3>
                <p class="text-sm text-yellow-700 mb-4">Pastikan semua field wajib telah diisi dengan benar sebelum
                    melanjutkan registrasi.</p>

                <button type="button" disabled
                    class="w-full inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-500 bg-gray-100 cursor-not-allowed">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 0h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                    Daftar Sekarang
                </button>
            </div>
        </div>
    @endif
</div>
