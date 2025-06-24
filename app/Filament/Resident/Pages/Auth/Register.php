<?php

namespace App\Filament\Resident\Pages\Auth;

use App\Models\User;
use App\Models\Family;
use Filament\Forms\Form;
use Filament\Facades\Filament;
use Filament\Forms\Components\Grid;
use Filament\Events\Auth\Registered;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Validation\Rules\Password;
use Filament\Forms\Components\Placeholder;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

class Register extends BaseRegister
{
    public function getMaxWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Informasi Akun')
                        ->icon('heroicon-m-user-circle')
                        ->description('Buat akun untuk mengakses sistem')
                        ->schema([
                            Section::make('Informasi Login')
                                ->description('Data yang akan digunakan untuk masuk ke sistem Villa Windaro Permai')
                                ->icon('heroicon-m-key')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('name')
                                                ->label('Nama Lengkap')
                                                ->required()
                                                ->maxLength(255)
                                                ->placeholder('Masukkan nama lengkap sesuai KTP')
                                                ->helperText('Nama harus sesuai dengan yang tertera di KTP')
                                                ->validationAttribute('nama lengkap'),
                                            TextInput::make('email')
                                                ->label('Alamat Email')
                                                ->email()
                                                ->required()
                                                ->maxLength(255)
                                                ->unique(User::class)
                                                ->placeholder('contoh@email.com')
                                                ->helperText('Email akan digunakan untuk notifikasi sistem')
                                                ->validationAttribute('email'),
                                        ]),
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('password')
                                                ->label('Kata Sandi')
                                                ->password()
                                                ->required()
                                                ->rule(Password::default()->mixedCase()->numbers()->symbols())
                                                ->same('passwordConfirmation')
                                                ->placeholder('Minimal 8 karakter')
                                                ->helperText('Gunakan kombinasi huruf besar, kecil, angka, dan simbol')
                                                ->validationAttribute('kata sandi'),
                                            TextInput::make('passwordConfirmation')
                                                ->label('Konfirmasi Kata Sandi')
                                                ->password()
                                                ->required()
                                                ->dehydrated(false)
                                                ->placeholder('Ulangi kata sandi')
                                                ->helperText('Harus sama dengan kata sandi di atas')
                                                ->validationAttribute('konfirmasi kata sandi'),
                                        ]),
                                ]),
                        ]),

                    Wizard\Step::make('Data Pribadi')
                        ->icon('heroicon-m-identification')
                        ->description('Informasi pribadi dan dokumen')
                        ->schema([
                            Section::make('Identitas Pribadi')
                                ->description('Data sesuai dengan dokumen resmi (KTP/KK)')
                                ->icon('heroicon-m-document-text')
                                ->schema([
                                    Grid::make(3)
                                        ->schema([
                                            TextInput::make('nik')
                                                ->label('NIK (Nomor Induk Kependudukan)')
                                                ->required()
                                                ->numeric()
                                                ->length(16)
                                                ->unique(User::class)
                                                ->placeholder('16 digit NIK dari KTP')
                                                ->helperText('NIK harus sesuai dengan KTP yang berlaku')
                                                ->validationAttribute('NIK'),
                                            TextInput::make('phone')
                                                ->label('Nomor Telepon/WhatsApp')
                                                ->tel()
                                                ->required()
                                                ->maxLength(20)
                                                ->placeholder('08xxxxxxxxxx')
                                                ->helperText('Nomor aktif untuk komunikasi darurat')
                                                ->validationAttribute('nomor telepon'),
                                            DatePicker::make('birth_date')
                                                ->label('Tanggal Lahir')
                                                ->required()
                                                ->maxDate(now()->subYears(17))
                                                ->helperText('Minimal berusia 17 tahun')
                                                ->validationAttribute('tanggal lahir'),
                                        ]),
                                    Grid::make(2)
                                        ->schema([
                                            Select::make('gender')
                                                ->label('Jenis Kelamin')
                                                ->required()
                                                ->options([
                                                    'laki-laki' => 'Laki-laki',
                                                    'perempuan' => 'Perempuan',
                                                ])
                                                ->validationAttribute('jenis kelamin'),
                                            TextInput::make('occupation')
                                                ->label('Pekerjaan')
                                                ->required()
                                                ->maxLength(100)
                                                ->placeholder('Contoh: Karyawan Swasta, Wiraswasta, PNS')
                                                ->helperText('Pekerjaan utama saat ini')
                                                ->validationAttribute('pekerjaan'),
                                        ]),
                                ]),
                        ]),

                    Wizard\Step::make('Informasi Hunian')
                        ->icon('heroicon-m-home-modern')
                        ->description('Data tempat tinggal di Villa Windaro Permai')
                        ->schema([
                            Section::make('Data Hunian')
                                ->description('Informasi rumah dan kepemilikan di Villa Windaro Permai')
                                ->icon('heroicon-m-building-office-2')
                                ->schema([
                                    Grid::make(3)
                                        ->schema([
                                            TextInput::make('house_number')
                                                ->label('Nomor/Blok Rumah')
                                                ->required()
                                                ->maxLength(10)
                                                ->placeholder('Contoh: A1, B12, C5')
                                                ->helperText('Sesuai dengan plang rumah di Villa Windaro Permai')
                                                ->validationAttribute('nomor rumah'),
                                            TextInput::make('kk_number')
                                                ->label('Nomor Kartu Keluarga')
                                                ->required()
                                                ->numeric()
                                                ->length(16)
                                                ->placeholder('16 digit nomor KK')
                                                ->helperText('Nomor KK akan diverifikasi dengan data yang ada')
                                                ->validationAttribute('nomor KK'),
                                            Select::make('house_status')
                                                ->label('Status Kepemilikan')
                                                ->required()
                                                ->options([
                                                    'owner' => 'Pemilik',
                                                    'tenant' => 'Penyewa/Kontrak',
                                                    'family' => 'Keluarga Pemilik',
                                                ])
                                                ->default('owner')
                                                ->helperText('Status hunian di Villa Windaro Permai')
                                                ->validationAttribute('status kepemilikan'),
                                        ]),
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('emergency_contact')
                                                ->label('Kontak Darurat')
                                                ->tel()
                                                ->maxLength(20)
                                                ->placeholder('Nomor keluarga/kerabat terdekat')
                                                ->helperText('Nomor yang dapat dihubungi dalam keadaan darurat'),
                                            TextInput::make('emergency_contact_relation')
                                                ->label('Hubungan Kontak Darurat')
                                                ->maxLength(50)
                                                ->placeholder('Contoh: Istri, Anak, Orangtua, Saudara')
                                                ->helperText('Hubungan dengan kontak darurat'),
                                        ]),
                                ]),

                            Section::make('Informasi Kendaraan (Opsional)')
                                ->description('Data kendaraan yang sering digunakan (untuk keperluan keamanan)')
                                ->icon('heroicon-m-truck')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('vehicle_1_plate')
                                                ->label('Plat Nomor Kendaraan 1')
                                                ->maxLength(15)
                                                ->placeholder('B 1234 XYZ')
                                                ->helperText('Kendaraan utama yang sering digunakan'),
                                            TextInput::make('vehicle_1_type')
                                                ->label('Jenis Kendaraan 1')
                                                ->maxLength(50)
                                                ->placeholder('Honda Civic, Toyota Avanza, dll')
                                                ->helperText('Merk dan model kendaraan'),
                                        ]),
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('vehicle_2_plate')
                                                ->label('Plat Nomor Kendaraan 2')
                                                ->maxLength(15)
                                                ->placeholder('B 5678 ABC')
                                                ->helperText('Kendaraan kedua (jika ada)'),
                                            TextInput::make('vehicle_2_type')
                                                ->label('Jenis Kendaraan 2')
                                                ->maxLength(50)
                                                ->placeholder('Motor, Mobil lain, dll')
                                                ->helperText('Merk dan model kendaraan kedua'),
                                        ]),
                                ]),
                        ]),

                    Wizard\Step::make('Notifikasi & Komunikasi')
                        ->icon('heroicon-m-chat-bubble-left-right')
                        ->description('Pengaturan komunikasi dan notifikasi')
                        ->schema([
                            Section::make('Notifikasi Telegram')
                                ->description('Pengaturan untuk menerima notifikasi melalui Telegram')
                                ->icon('heroicon-m-paper-airplane')
                                ->schema([
                                    Toggle::make('enable_telegram')
                                        ->label('Aktifkan Notifikasi Telegram')
                                        ->helperText('Terima pengumuman dan update penting via Telegram')
                                        ->default(false)
                                        ->live(),
                                    TextInput::make('telegram_chat_id')
                                        ->label('Telegram Chat ID')
                                        ->numeric()
                                        ->placeholder('Chat ID Telegram Anda')
                                        ->helperText('Dapatkan Chat ID dari @userinfobot di Telegram')
                                        ->visible(fn(\Filament\Forms\Get $get) => $get('enable_telegram')),
                                    Placeholder::make('telegram_help')
                                        ->label('Cara mendapatkan Chat ID:')
                                        ->content(function () {
                                            return new \Illuminate\Support\HtmlString('
                                                <ol style="margin: 0; padding-left: 20px;">
                                                    <li>Buka Telegram dan cari <strong>@userinfobot</strong></li>
                                                    <li>Kirim pesan <strong>/start</strong></li>
                                                    <li>Bot akan mengirim Chat ID Anda</li>
                                                    <li>Salin angka Chat ID tersebut</li>
                                                </ol>
                                            ');
                                        })
                                        ->visible(fn(\Filament\Forms\Get $get) => $get('enable_telegram')),
                                ]),

                            Section::make('Preferensi Komunikasi')
                                ->description('Pilih jenis informasi yang ingin Anda terima')
                                ->icon('heroicon-m-cog-6-tooth')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            Toggle::make('notify_announcements')
                                                ->label('Pengumuman Umum')
                                                ->helperText('Notifikasi pengumuman dari pengurus')
                                                ->default(true),
                                            Toggle::make('notify_financial')
                                                ->label('Informasi Keuangan')
                                                ->helperText('Laporan keuangan dan tagihan')
                                                ->default(true),
                                        ]),
                                    Grid::make(2)
                                        ->schema([
                                            Toggle::make('notify_events')
                                                ->label('Acara dan Kegiatan')
                                                ->helperText('Undangan acara dan kegiatan warga')
                                                ->default(true),
                                            Toggle::make('notify_security')
                                                ->label('Informasi Keamanan')
                                                ->helperText('Update keamanan dan situasi darurat')
                                                ->default(true),
                                        ]),
                                ]),
                        ]),

                    Wizard\Step::make('Verifikasi & Konfirmasi')
                        ->icon('heroicon-m-shield-check')
                        ->description('Konfirmasi data dan persetujuan')
                        ->schema([
                            Section::make('Verifikasi Data')
                                ->description('Pastikan semua data yang Anda masukkan sudah benar')
                                ->icon('heroicon-m-document-check')
                                ->schema([
                                    Placeholder::make('verification_info')
                                        ->label('Informasi Penting:')
                                        ->content(function () {
                                            return new \Illuminate\Support\HtmlString('
                                                <div style="background: #fef5e7; border: 1px solid #f6ad55; border-radius: 8px; padding: 15px; margin: 10px 0;">
                                                    <ul style="margin: 0; padding-left: 20px; color: #744210;">
                                                        <li><strong>Data akan diverifikasi</strong> oleh admin dalam 1-2 hari kerja</li>
                                                        <li><strong>NIK dan nomor KK</strong> akan dicocokkan dengan database resmi</li>
                                                        <li><strong>Akun akan aktif</strong> setelah verifikasi selesai</li>
                                                        <li><strong>Notifikasi status</strong> akan dikirim via email</li>
                                                    </ul>
                                                </div>
                                            ');
                                        }),

                                    Grid::make(1)
                                        ->schema([
                                            Select::make('data_verification')
                                                ->label('Konfirmasi Kebenaran Data')
                                                ->required()
                                                ->options([
                                                    'verified' => 'Ya, saya konfirmasi semua data yang saya masukkan adalah benar dan sesuai dengan dokumen resmi',
                                                ])
                                                ->placeholder('Pilih konfirmasi')
                                                ->helperText('Pastikan data NIK, KK, dan alamat rumah sudah benar')
                                                ->validationAttribute('konfirmasi data'),
                                        ]),
                                ]),

                            Section::make('Persetujuan')
                                ->description('Ketentuan penggunaan sistem')
                                ->icon('heroicon-m-document-text')
                                ->schema([
                                    Toggle::make('agree_terms')
                                        ->label('Saya menyetujui syarat dan ketentuan penggunaan sistem')
                                        ->required()
                                        ->helperText('Wajib disetujui untuk melanjutkan pendaftaran')
                                        ->validationAttribute('persetujuan syarat'),

                                    Toggle::make('agree_privacy')
                                        ->label('Saya menyetujui kebijakan privasi dan penggunaan data')
                                        ->required()
                                        ->helperText('Data pribadi akan dijaga kerahasiaannya sesuai kebijakan')
                                        ->validationAttribute('persetujuan privasi'),

                                    Textarea::make('additional_notes')
                                        ->label('Catatan Tambahan (Opsional)')
                                        ->placeholder('Jika ada informasi tambahan yang perlu disampaikan kepada admin...')
                                        ->rows(3)
                                        ->helperText('Catatan khusus atau informasi tambahan untuk admin'),
                                ]),
                        ]),
                ])
                    ->columnSpanFull()
                    ->skippable()
                    ->persistStepInQueryString()
            ]);
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

        // $this->sendEmailVerificationNotification($user);

        Filament::auth()->login($user);

        session()->regenerate();

        return app(RegistrationResponse::class);
    }

    protected function handleRegistration(array $data): Model
    {
        $family = Family::where('kk_number', $data['kk_number'])->first();

        // Prepare user data with safe fallbacks
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'nik' => $data['nik'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'house_number' => $data['house_number'],
            'kk_number' => $data['kk_number'],
            'telegram_chat_id' => ($data['enable_telegram'] ?? false) ? ($data['telegram_chat_id'] ?? null) : null,
            'role' => 'resident',
            'is_active' => false, // Requires admin approval
        ];

        // Add additional fields only if they exist and are not empty
        $additionalFields = [
            'birth_date' => $data['birth_date'] ?? null,
            'gender' => $data['gender'] ?? null,
            'occupation' => $data['occupation'] ?? null,
            'house_status' => $data['house_status'] ?? 'owner',
            'emergency_contact' => !empty($data['emergency_contact']) ? $data['emergency_contact'] : null,
            'emergency_contact_relation' => !empty($data['emergency_contact_relation']) ? $data['emergency_contact_relation'] : null,
            'vehicle_1_plate' => !empty($data['vehicle_1_plate']) ? $data['vehicle_1_plate'] : null,
            'vehicle_1_type' => !empty($data['vehicle_1_type']) ? $data['vehicle_1_type'] : null,
            'vehicle_2_plate' => !empty($data['vehicle_2_plate']) ? $data['vehicle_2_plate'] : null,
            'vehicle_2_type' => !empty($data['vehicle_2_type']) ? $data['vehicle_2_type'] : null,
            'notify_announcements' => $data['notify_announcements'] ?? true,
            'notify_financial' => $data['notify_financial'] ?? true,
            'notify_events' => $data['notify_events'] ?? true,
            'notify_security' => $data['notify_security'] ?? true,
            'additional_notes' => !empty($data['additional_notes']) ? $data['additional_notes'] : null,
        ];

        // Merge data
        $userData = array_merge($userData, $additionalFields);

        $user = User::create($userData);

        // Link dengan family jika sudah ada
        if ($family && !$family->user_id) {
            $family->update(['user_id' => $user->id]);
        }

        // Send notification to admin
        Notification::make()
            ->title('Pendaftaran Berhasil!')
            ->body('Akun Anda akan diverifikasi dalam 1-2 hari kerja. Kami akan mengirim email konfirmasi setelah verifikasi selesai.')
            ->success()
            ->persistent()
            ->send();

        return $user;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getLoginUrl();
    }

    protected function getLoginUrl(): string
    {
        return filament()->getLoginUrl();
    }

    public function mount(): void
    {
        parent::mount();

        $this->form->fill();
    }
}
