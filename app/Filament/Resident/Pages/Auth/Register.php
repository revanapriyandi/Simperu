<?php

namespace App\Filament\Resident\Pages\Auth;

use App\Models\User;
use App\Models\Family;
use App\Models\FamilyMember;
use Filament\Forms\Form;
use App\Enums\HouseStatus;
use Filament\Facades\Filament;
use Filament\Forms\Components\Grid;
use Filament\Events\Auth\Registered;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use App\Services\RegistrationValidationService;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Register extends BaseRegister
{
    protected static string $view = 'filament.resident.pages.auth.register';
    protected ?string $maxWidth = '4xl';
    protected static ?string $title = 'Daftar Akun Warga';
    protected static ?string $navigationLabel = 'Daftar';

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        // Jangan panggil parent::mount() untuk menghindari validasi prematur
        $this->form->fill([]);
    }

    public function hasUnsavedDataChangesAlert(): bool
    {
        return false;
    }

    protected function getFormStatePath(): ?string
    {
        return 'data';
    }

    public function getTitle(): string
    {
        return 'Daftar Akun Warga Baru';
    }

    public function getHeading(): string
    {
        return 'Bergabung dengan Komunitas Digital';
    }

    public function getSubheading(): ?string
    {
        return 'Lengkapi formulir registrasi untuk mendapatkan akses penuh ke layanan perumahan';
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Wizard::make([
                Wizard\Step::make('personal')
                    ->label('Data Pribadi')
                    ->description('Informasi identitas Anda')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Grid::make(['default' => 1, 'md' => 2])->schema([
                            TextInput::make('name')
                                ->label('Nama Lengkap')
                                ->maxLength(255)
                                ->autofocus()
                                ->placeholder('Masukkan nama lengkap sesuai KTP')
                                ->prefixIcon('heroicon-o-user')
                                ->columnSpan(['default' => 1, 'md' => 2]),

                            TextInput::make('nik')
                                ->label('Nomor Induk Kependudukan (NIK)')
                                ->placeholder('16 digit NIK sesuai KTP')
                                ->prefixIcon('heroicon-o-identification')
                                ->helperText('Nomor NIK sesuai KTP')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    if ($state && strlen($state) === 16) {
                                        $validationService = app(RegistrationValidationService::class);
                                        $nikValidation = $validationService->validateNik($state);

                                        if ($nikValidation['is_registered']) {
                                            $this->addError('data.nik', $nikValidation['message']);
                                        }
                                    }
                                }),

                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->placeholder('contoh@email.com')
                                ->prefixIcon('heroicon-o-envelope'),

                            TextInput::make('phone')
                                ->label('Nomor Telepon')
                                ->placeholder('08xxxxxxxxxx')
                                ->prefixIcon('heroicon-o-phone')
                                ->tel(),
                        ])
                    ]),

                Wizard\Step::make('family')
                    ->label('Data Keluarga')
                    ->description('Informasi Kartu Keluarga')
                    ->icon('heroicon-o-home')
                    ->schema([
                        Section::make('Informasi Kartu Keluarga')
                            ->description('Masukkan nomor KK untuk bergabung dengan keluarga yang sudah terdaftar atau buat keluarga baru')
                            ->schema([
                                TextInput::make('kk_number')
                                    ->label('Nomor Kartu Keluarga (KK)')
                                    ->placeholder('16 digit nomor KK')
                                    ->prefixIcon('heroicon-o-home')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state && strlen($state) === 16) {
                                            $validationService = app(RegistrationValidationService::class);
                                            $kkValidation = $validationService->validateKkNumber($state);

                                            if ($kkValidation['is_registered']) {
                                                // KK sudah ada - registrasi sebagai anggota keluarga
                                                $family = Family::where('kk_number', $state)->first();
                                                if ($family) {
                                                    $set('family_name', $family->family_name);
                                                    $set('address', $family->address);
                                                    $set('registration_type', 'family_member');

                                                    // Validasi apakah bisa daftar sebagai anggota keluarga
                                                    $memberValidation = $validationService->canRegisterAsFamilyMember($state, $get('nik'));
                                                    if (!$memberValidation['can_register']) {
                                                        $this->addError('data.kk_number', $memberValidation['message']);
                                                    }
                                                }
                                            } else {
                                                // KK belum ada - registrasi sebagai kepala keluarga
                                                $set('family_name', '');
                                                $set('address', '');
                                                $set('registration_type', 'head_of_family');

                                                // Validasi apakah bisa daftar sebagai kepala keluarga
                                                $headValidation = $validationService->canRegisterAsHeadOfFamily($state, $get('nik'));
                                                if (!$headValidation['can_register']) {
                                                    $this->addError('data.kk_number', $headValidation['message']);
                                                }
                                            }
                                        }
                                    }),

                                Placeholder::make('registration_info')
                                    ->label('')
                                    ->content(function (callable $get) {
                                        $kkNumber = $get('kk_number');
                                        $registrationType = $get('registration_type');

                                        if (!$kkNumber || strlen($kkNumber) !== 16) {
                                            return 'Masukkan nomor KK yang valid untuk melihat informasi registrasi.';
                                        }

                                        if ($registrationType === 'family_member') {
                                            return '✅ **Bergabung dengan keluarga yang sudah terdaftar**. Data keluarga akan diisi otomatis.';
                                        } elseif ($registrationType === 'head_of_family') {
                                            return '📋 **Mendaftar sebagai kepala keluarga baru**. Anda perlu mengisi data keluarga.';
                                        }

                                        return 'Memvalidasi nomor KK...';
                                    })
                                    ->columnSpan(2),

                                TextInput::make('family_name')
                                    ->label('Nama Kepala Keluarga')
                                    ->placeholder('Nama kepala keluarga')
                                    ->prefixIcon('heroicon-o-user-group')
                                    ->disabled(fn(callable $get) => $get('registration_type') === 'family_member')
                                    ->dehydrated(),

                                TextInput::make('address')
                                    ->label('Alamat Lengkap')
                                    ->placeholder('Alamat sesuai KK')
                                    ->prefixIcon('heroicon-o-map-pin')
                                    ->disabled(fn(callable $get) => $get('registration_type') === 'family_member')
                                    ->dehydrated()
                                    ->columnSpan(2),

                                Select::make('house_status')
                                    ->label('Status Rumah')
                                    ->options(HouseStatus::class)
                                    ->placeholder('Pilih status kepemilikan rumah')
                                    ->prefixIcon('heroicon-o-building-office')
                                    ->disabled(fn(callable $get) => $get('registration_type') === 'family_member')
                                    ->dehydrated(),

                                // Hidden field untuk menyimpan tipe registrasi
                                TextInput::make('registration_type')
                                    ->hidden()
                                    ->dehydrated(),
                            ])
                    ]),

                Wizard\Step::make('credentials')
                    ->label('Keamanan')
                    ->description('Buat kata sandi akun')
                    ->icon('heroicon-o-lock-closed')
                    ->schema([
                        Grid::make(['default' => 1, 'md' => 2])->schema([
                            TextInput::make('password')
                                ->label('Kata Sandi')
                                ->password()
                                ->placeholder('Minimal 8 karakter')
                                ->prefixIcon('heroicon-o-lock-closed')
                                ->revealable(),

                            TextInput::make('password_confirmation')
                                ->label('Konfirmasi Kata Sandi')
                                ->password()
                                ->placeholder('Ulangi kata sandi')
                                ->prefixIcon('heroicon-o-lock-closed')
                                ->revealable()
                                ->dehydrated(false),

                            Placeholder::make('password_requirements')
                                ->label('Persyaratan Kata Sandi')
                                ->content('
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Minimal 8 karakter</li>
                                            <li>Kombinasi huruf dan angka disarankan</li>
                                            <li>Hindari kata sandi yang mudah ditebak</li>
                                        </ul>
                                    </div>
                                ')
                                ->columnSpan(2),
                        ])
                    ]),

                Wizard\Step::make('confirmation')
                    ->label('Konfirmasi')
                    ->description('Periksa data sebelum mendaftar')
                    ->icon('heroicon-o-check-circle')
                    ->schema([
                        Section::make('Ringkasan Pendaftaran')
                            ->description('Pastikan semua data sudah benar sebelum melanjutkan')
                            ->schema([
                                Placeholder::make('summary')
                                    ->label('')
                                    ->content(function (callable $get) {
                                        $name = $get('name') ?: '-';
                                        $nik = $get('nik') ?: '-';
                                        $email = $get('email') ?: '-';
                                        $phone = $get('phone') ?: '-';
                                        $kkNumber = $get('kk_number') ?: '-';
                                        $familyName = $get('family_name') ?: '-';
                                        $address = $get('address') ?: '-';
                                        $registrationType = $get('registration_type');

                                        $typeLabel = $registrationType === 'family_member'
                                            ? 'Anggota Keluarga'
                                            : 'Kepala Keluarga Baru';

                                        return "
                                            <div class='space-y-4'>
                                                <div class='grid grid-cols-1 md:grid-cols-2 gap-4'>
                                                    <div>
                                                        <h4 class='font-semibold text-gray-900 dark:text-gray-100'>Data Pribadi</h4>
                                                        <div class='mt-2 space-y-1 text-sm text-gray-600 dark:text-gray-400'>
                                                            <p><span class='font-medium'>Nama:</span> {$name}</p>
                                                            <p><span class='font-medium'>NIK:</span> {$nik}</p>
                                                            <p><span class='font-medium'>Email:</span> {$email}</p>
                                                            <p><span class='font-medium'>Telepon:</span> {$phone}</p>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h4 class='font-semibold text-gray-900 dark:text-gray-100'>Data Keluarga</h4>
                                                        <div class='mt-2 space-y-1 text-sm text-gray-600 dark:text-gray-400'>
                                                            <p><span class='font-medium'>Tipe Registrasi:</span> {$typeLabel}</p>
                                                            <p><span class='font-medium'>No. KK:</span> {$kkNumber}</p>
                                                            <p><span class='font-medium'>Kepala Keluarga:</span> {$familyName}</p>
                                                            <p><span class='font-medium'>Alamat:</span> {$address}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        ";
                                    })
                                    ->columnSpan(2),

                                // Form completion status and register button
                                \Filament\Forms\Components\View::make('filament.components.registration-form-status')
                                    ->viewData([
                                        'isComplete' => fn() => $this->isFormComplete()
                                    ])
                            ])
                    ])
            ])
        ])->columns(1);
    }

    public function isFormComplete(): bool
    {
        $data = $this->form->getState();

        $requiredFields = [
            'name',
            'nik',
            'email',
            'phone',
            'kk_number',
            'password'
        ];

        // Check basic required fields
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }

        // Check conditional fields based on registration type
        if (($data['registration_type'] ?? '') !== 'family_member') {
            $headOfFamilyFields = ['family_name', 'address', 'house_status'];
            foreach ($headOfFamilyFields as $field) {
                if (empty($data[$field])) {
                    return false;
                }
            }
        }

        // Validasi format NIK dan KK
        if (!preg_match('/^[0-9]{16}$/', $data['nik'] ?? '')) {
            return false;
        }

        if (!preg_match('/^[0-9]{16}$/', $data['kk_number'] ?? '')) {
            return false;
        }

        // Validasi email
        if (!filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // Validasi password minimal 8 karakter
        if (strlen($data['password'] ?? '') < 8) {
            return false;
        }

        return true;
    }

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            \Filament\Notifications\Notification::make()
                ->title(__('filament-panels::pages/auth/register.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/register.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/register.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        // Manual validation
        $errors = [];

        if (empty($data['name'])) {
            $errors['data.name'] = 'Nama lengkap wajib diisi.';
        } elseif (strlen($data['name']) < 3) {
            $errors['data.name'] = 'Nama minimal 3 karakter.';
        }

        if (empty($data['nik'])) {
            $errors['data.nik'] = 'NIK wajib diisi.';
        } elseif (!preg_match('/^[0-9]{16}$/', $data['nik'])) {
            $errors['data.nik'] = 'NIK harus 16 digit angka.';
        } elseif (User::where('nik', $data['nik'])->exists()) {
            $errors['data.nik'] = 'NIK sudah terdaftar dalam sistem.';
        }

        if (empty($data['email'])) {
            $errors['data.email'] = 'Email wajib diisi.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['data.email'] = 'Format email tidak valid.';
        } elseif (User::where('email', $data['email'])->exists()) {
            $errors['data.email'] = 'Email sudah terdaftar.';
        }

        if (empty($data['phone'])) {
            $errors['data.phone'] = 'Nomor telepon wajib diisi.';
        } elseif (!preg_match('/^08[0-9]{8,11}$/', $data['phone'])) {
            $errors['data.phone'] = 'Nomor telepon harus diawali 08 dan berisi 10-13 digit.';
        }

        if (empty($data['kk_number'])) {
            $errors['data.kk_number'] = 'Nomor KK wajib diisi.';
        } elseif (!preg_match('/^[0-9]{16}$/', $data['kk_number'])) {
            $errors['data.kk_number'] = 'Nomor KK harus 16 digit angka.';
        }

        if (empty($data['password'])) {
            $errors['data.password'] = 'Kata sandi wajib diisi.';
        } elseif (strlen($data['password']) < 8) {
            $errors['data.password'] = 'Kata sandi minimal 8 karakter.';
        } elseif ($data['password'] !== $data['password_confirmation']) {
            $errors['data.password_confirmation'] = 'Konfirmasi kata sandi tidak cocok.';
        }

        // Validate conditional fields
        if (($data['registration_type'] ?? '') !== 'family_member') {
            if (empty($data['family_name'])) {
                $errors['data.family_name'] = 'Nama kepala keluarga wajib diisi.';
            }
            if (empty($data['address'])) {
                $errors['data.address'] = 'Alamat wajib diisi.';
            }
            if (empty($data['house_status'])) {
                $errors['data.house_status'] = 'Status rumah wajib dipilih.';
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }

        // Validasi data menggunakan service
        $validationService = app(RegistrationValidationService::class);
        $validation = $validationService->validateRegistrationData($data);

        if (!$validation['valid']) {
            throw ValidationException::withMessages([
                'general' => $validation['message']
            ]);
        }

        try {
            DB::beginTransaction();

            // Use our custom user creation method
            $user = $this->getUserFromData($data);

            event(new Registered($user));

            Filament::auth()->login($user);

            session()->regenerate();

            DB::commit();

            \Filament\Notifications\Notification::make()
                ->title('Registrasi Berhasil!')
                ->body('Selamat datang di sistem perumahan. Akun Anda telah berhasil dibuat.')
                ->success()
                ->send();

            return app(RegistrationResponse::class);
        } catch (\Exception $e) {
            DB::rollBack();

            \Filament\Notifications\Notification::make()
                ->title('Registrasi Gagal')
                ->body('Terjadi kesalahan saat mendaftar: ' . $e->getMessage())
                ->danger()
                ->send();

            throw $e;
        }
    }

    protected function registerAsHeadOfFamily(array $data): User
    {
        // Buat keluarga baru
        $family = Family::create([
            'kk_number' => $data['kk_number'],
            'family_name' => $data['family_name'],
            'address' => $data['address'],
            'house_status' => $data['house_status'],
        ]);

        // Buat user sebagai kepala keluarga
        $user = User::create([
            'name' => $data['name'],
            'nik' => $data['nik'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'family_id' => $family->id,
        ]);

        // Buat record family member untuk kepala keluarga
        FamilyMember::create([
            'family_id' => $family->id,
            'user_id' => $user->id,
            'nik' => $data['nik'],
            'name' => $data['name'],
            'relationship' => 'Kepala Keluarga',
            'is_head' => true,
        ]);

        return $user;
    }

    protected function registerAsFamilyMember(array $data): User
    {
        // Cari keluarga berdasarkan nomor KK
        $family = Family::where('kk_number', $data['kk_number'])->firstOrFail();

        // Buat user sebagai anggota keluarga
        $user = User::create([
            'name' => $data['name'],
            'nik' => $data['nik'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'family_id' => $family->id,
        ]);

        // Buat record family member
        FamilyMember::create([
            'family_id' => $family->id,
            'user_id' => $user->id,
            'nik' => $data['nik'],
            'name' => $data['name'],
            'relationship' => 'Anggota Keluarga',
            'is_head' => false,
        ]);

        return $user;
    }

    protected function getUserFromData(array $data): Authenticatable
    {
        // This method is required by the base class but we handle registration differently
        // The actual user creation is done in register() method
        if ($data['registration_type'] === 'family_member') {
            return $this->registerAsFamilyMember($data);
        } else {
            return $this->registerAsHeadOfFamily($data);
        }
    }
}
