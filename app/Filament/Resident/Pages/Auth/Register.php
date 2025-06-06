<?php

namespace App\Filament\Resident\Pages\Auth;

use App\Models\Family;
use Filament\Forms\Form;
use App\Enums\HouseStatus;
use Filament\Facades\Filament;
use Filament\Forms\Components\Grid;
use Filament\Events\Auth\Registered;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

class Register extends BaseRegister
{
    protected static string $view = 'filament.resident.pages.auth.register';

    protected ?string $maxWidth = '4xl';

    protected static ?string $title = 'Daftar Akun Warga';

    protected static ?string $navigationLabel = 'Daftar';

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
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('personal')
                        ->label('Data Pribadi')
                        ->description('Informasi identitas Anda')
                        ->icon('heroicon-o-user')
                        ->schema([
                            Grid::make()
                                ->columns([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 2,
                                    'lg' => 2,
                                ])
                                ->schema([
                                    TextInput::make('name')
                                        ->label('Nama Lengkap')
                                        ->required()
                                        ->maxLength(255)
                                        ->autofocus()
                                        ->placeholder('Masukkan nama lengkap sesuai KTP')
                                        ->prefixIcon('heroicon-o-user')
                                        ->rule('string')
                                        ->rule('min:3')
                                        ->columnSpan([
                                            'default' => 1,
                                            'md' => 2,
                                        ])
                                        ->validationMessages([
                                            'required' => 'Nama lengkap wajib diisi.',
                                            'min' => 'Nama minimal 3 karakter.',
                                        ]),

                                    TextInput::make('email')
                                        ->label('Alamat Email')
                                        ->email()
                                        ->required()
                                        ->maxLength(255)
                                        ->unique($this->getUserModel())
                                        ->placeholder('contoh@email.com')
                                        ->prefixIcon('heroicon-o-envelope')
                                        ->validationMessages([
                                            'required' => 'Email wajib diisi.',
                                            'email' => 'Format email tidak valid.',
                                            'unique' => 'Email sudah terdaftar.',
                                        ]),

                                    TextInput::make('phone')
                                        ->label('Nomor WhatsApp/Telepon')
                                        ->tel()
                                        ->maxLength(20)
                                        ->placeholder('08xxxxxxxxxx')
                                        ->prefixIcon('heroicon-o-device-phone-mobile')
                                        ->regex('/^08[0-9]{8,}$/')
                                        ->validationMessages([
                                            'regex' => 'Nomor telepon harus diawali 08 dan minimal 10 digit.'
                                        ])
                                        ->nullable(),
                                ])
                        ]),

                    Wizard\Step::make('family')
                        ->label('Data Keluarga')
                        ->description('Informasi Kartu Keluarga')
                        ->icon('heroicon-o-home')
                        ->schema([
                            Grid::make()
                                ->columns([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 2,
                                    'lg' => 2,
                                ])
                                ->schema([
                                    TextInput::make('kk_number')
                                        ->label('Nomor Kartu Keluarga (KK)')
                                        ->required()
                                        ->placeholder('16 digit nomor KK')
                                        ->prefixIcon('heroicon-o-identification')
                                        ->helperText('Sesuai dokumen Kartu Keluarga resmi')
                                        ->regex('/^[0-9]{16}$/')
                                        ->unique(\App\Models\Family::class, 'kk_number')
                                        ->validationMessages([
                                            'regex' => 'Nomor KK harus 16 digit angka.',
                                            'unique' => 'Nomor KK sudah terdaftar.',
                                            'required' => 'Nomor KK wajib diisi.',
                                        ])
                                        ->rule('numeric')
                                        ->columnSpan([
                                            'default' => 1,
                                            'md' => 2,
                                        ]),

                                    TextInput::make('head_of_family')
                                        ->label('Nama Kepala Keluarga')
                                        ->required()
                                        ->maxLength(255)
                                        ->placeholder('Sesuai yang tertera di KK')
                                        ->prefixIcon('heroicon-o-user-circle')
                                        ->helperText('Jika Anda kepala keluarga, isi dengan nama Anda')
                                        ->rule('string')
                                        ->rule('min:3')
                                        ->validationMessages([
                                            'required' => 'Nama kepala keluarga wajib diisi.',
                                            'min' => 'Nama minimal 3 karakter.',
                                        ]),

                                    TextInput::make('family_members_count')
                                        ->label('Jumlah Anggota Keluarga')
                                        ->numeric()
                                        ->required()
                                        ->minValue(1)
                                        ->maxValue(20)
                                        ->default(1)
                                        ->prefixIcon('heroicon-o-users')
                                        ->helperText('Total anggota dalam satu KK')
                                        ->validationMessages([
                                            'required' => 'Jumlah anggota keluarga wajib diisi.',
                                            'min' => 'Minimal 1 anggota keluarga.',
                                            'max' => 'Maksimal 20 anggota keluarga.',
                                        ]),
                                ])
                        ]),

                    Wizard\Step::make('housing')
                        ->label('Data Hunian')
                        ->description('Informasi tempat tinggal')
                        ->icon('heroicon-o-building-office-2')
                        ->schema([
                            Grid::make()
                                ->columns([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 3,
                                    'lg' => 3,
                                ])
                                ->schema([
                                    TextInput::make('house_number')
                                        ->label('Nomor Rumah')
                                        ->required()
                                        ->maxLength(10)
                                        ->placeholder('01, 15, 123')
                                        ->prefixIcon('heroicon-o-home')
                                        ->helperText('Nomor rumah/unit')
                                        ->validationMessages([
                                            'required' => 'Nomor rumah wajib diisi.',
                                        ]),

                                    TextInput::make('house_block')
                                        ->label('Blok/RT')
                                        ->required()
                                        ->maxLength(10)
                                        ->placeholder('RT-01, A, B-15')
                                        ->prefixIcon('heroicon-o-map')
                                        ->helperText('Blok atau RT')
                                        ->rule('string')
                                        ->validationMessages([
                                            'required' => 'Blok/RT wajib diisi.',
                                        ]),

                                    Select::make('house_status')
                                        ->label('Status Hunian')
                                        ->required()
                                        ->options(HouseStatus::class)
                                        ->default('owner')
                                        ->helperText('Status kepemilikan')
                                        ->selectablePlaceholder(false)
                                        ->validationMessages([
                                            'required' => 'Status hunian wajib dipilih.',
                                        ]),

                                ])
                        ]),

                    Wizard\Step::make('security')
                        ->label('Keamanan')
                        ->description('Kata sandi akun')
                        ->icon('heroicon-o-shield-check')
                        ->schema([
                            Grid::make()
                                ->columns([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 2,
                                    'lg' => 2,
                                ])
                                ->schema([
                                    $this->getPasswordFormComponent()
                                        ->label('Kata Sandi')
                                        ->placeholder('Minimal 8 karakter')
                                        ->prefixIcon('heroicon-o-lock-closed')
                                        ->helperText('Gunakan kombinasi huruf, angka, dan simbol untuk keamanan maksimal')
                                        ->validationMessages([
                                            'required' => 'Kata sandi wajib diisi.',
                                            'min' => 'Kata sandi minimal 8 karakter.',
                                        ]),

                                    $this->getPasswordConfirmationFormComponent()
                                        ->label('Konfirmasi Kata Sandi')
                                        ->placeholder('Ulangi kata sandi yang sama')
                                        ->prefixIcon('heroicon-o-lock-closed')
                                        ->helperText('Pastikan kata sandi sama persis')
                                        ->validationMessages([
                                            'required' => 'Konfirmasi kata sandi wajib diisi.',
                                            'same' => 'Konfirmasi kata sandi tidak sama.',
                                        ]),
                                ]),

                            // Final step info and important notes
                            Placeholder::make('final_info')
                                ->content(new \Illuminate\Support\HtmlString('
                                            <div class="space-y-4 mt-6">
                                                <div class="bg-gradient-to-r from-orange-50 to-amber-50 border border-orange-200 rounded-xl p-4">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="flex-shrink-0">
                                                            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                                                <span class="text-orange-600 font-semibold text-sm">4</span>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h4 class="text-sm font-medium text-orange-900">Langkah Terakhir</h4>
                                                            <p class="text-sm text-orange-700">Buat kata sandi yang kuat untuk melindungi akun Anda</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 rounded-xl p-4">
                                                    <h4 class="text-sm font-semibold text-blue-900 mb-3 flex items-center">
                                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Informasi Penting
                                                    </h4>
                                                    <div class="grid gap-3">
                                                        <div class="flex items-start space-x-3">
                                                            <svg class="h-5 w-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            <p class="text-sm text-blue-800">Data harus sesuai dokumen resmi (KTP/KK)</p>
                                                        </div>
                                                        <div class="flex items-start space-x-3">
                                                            <svg class="h-5 w-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            <p class="text-sm text-blue-800">Verifikasi admin dalam 1x24 jam</p>
                                                        </div>
                                                        <div class="flex items-start space-x-3">
                                                            <svg class="h-5 w-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            <p class="text-sm text-blue-800">Notifikasi via email dan Telegram</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        ')),
                        ])
                ])
                    ->skippable(false)
                    ->persistStepInQueryString()
            ])
            ->columns(1);
    }

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $user = $this->wrapInDatabaseTransaction(function () {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeRegister($data);

            $this->callHook('beforeRegister');

            $user = $this->handleRegistration($data);

            $this->form->model($user)->saveRelationships();

            $this->callHook('afterRegister');

            return $user;
        });

        event(new Registered($user));

        Filament::auth()->login($user);

        session()->regenerate();

        return app(RegistrationResponse::class);
    }

    protected function handleRegistration(array $data): Model
    {
        try {
            // Ensure the role is set to resident
            $data['role'] = 'resident';
            $data['is_active'] = true;

            // Create user first
            $user = $this->getUserModel()::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'phone' => $data['phone'] ?? null,
                'house_number' => $data['house_number'],
                'kk_number' => $data['kk_number'],
                'role' => 'resident',
                'is_active' => true,
            ]);

            // Create family record
            \App\Models\Family::create([
                'kk_number' => $data['kk_number'],
                'head_of_family' => $data['head_of_family'],
                'house_block' => $data['house_block'],
                'phone_1' => $data['phone'] ?? null,
                'house_status' => $data['house_status'] ?? 'owner',
                'family_members_count' => $data['family_members_count'] ?? 1,
                'status' => 'active',
                'user_id' => $user->id,
            ]);

            return $user;
        } catch (\Exception $e) {
            // Log the error for debugging
            \Illuminate\Support\Facades\Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            // Re-throw with user-friendly message
            throw new \Exception('Registrasi gagal. Pastikan nomor KK belum terdaftar dan semua data sudah benar.');
        }
    }

    protected function mutateFormDataBeforeRegister(array $data): array
    {
        // Clean phone number format
        if (isset($data['phone'])) {
            $data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);
        }

        // Clean KK number
        if (isset($data['kk_number'])) {
            $data['kk_number'] = preg_replace('/[^0-9]/', '', $data['kk_number']);
        }

        // Capitalize names properly
        if (isset($data['name'])) {
            $data['name'] = ucwords(strtolower(trim($data['name'])));
        }

        if (isset($data['head_of_family'])) {
            $data['head_of_family'] = ucwords(strtolower(trim($data['head_of_family'])));
        }

        // Normalize house block format
        if (isset($data['house_block'])) {
            $data['house_block'] = strtoupper(trim($data['house_block']));
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->panel->getUrl();
    }
}
