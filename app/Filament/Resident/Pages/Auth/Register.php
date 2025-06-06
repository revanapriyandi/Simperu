<?php

namespace App\Filament\Resident\Pages\Auth;

use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Database\Eloquent\Model;

class Register extends BaseRegister
{
    protected static string $view = 'filament.resident.pages.auth.register';
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255)
                    ->autofocus()
                    ->placeholder('Masukkan nama lengkap Anda'),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique($this->getUserModel())
                    ->placeholder('contoh@email.com'),

                TextInput::make('phone')
                    ->label('Nomor Telepon')
                    ->tel()
                    ->maxLength(20)
                    ->placeholder('08xxxxxxxxxx')
                    ->helperText('Nomor telepon untuk komunikasi')
                    ->regex('/^08[0-9]{8,}$/')
                    ->validationMessages([
                        'regex' => 'Nomor telepon harus diawali dengan 08 dan minimal 10 digit.'
                    ])
                    ->nullable(),

                TextInput::make('house_number')
                    ->label('Nomor Rumah/Blok')
                    ->maxLength(10)
                    ->placeholder('Contoh: A-01, B-15, 123')
                    ->helperText('Nomor rumah atau blok tempat tinggal')
                    ->nullable(),

                TextInput::make('kk_number')
                    ->label('Nomor Kartu Keluarga (KK)')
                    ->maxLength(20)
                    ->placeholder('16 digit nomor KK')
                    ->helperText('Nomor Kartu Keluarga sesuai dokumen resmi')
                    ->regex('/^[0-9]{16}$/')
                    ->validationMessages([
                        'regex' => 'Nomor KK harus 16 digit angka.'
                    ])
                    ->nullable(),

                $this->getPasswordFormComponent()
                    ->label('Kata Sandi')
                    ->placeholder('Minimal 8 karakter'),

                $this->getPasswordConfirmationFormComponent()
                    ->label('Konfirmasi Kata Sandi')
                    ->placeholder('Ulangi kata sandi'),
            ]);
    }

    protected function handleRegistration(array $data): Model
    {
        // Ensure the role is set to resident
        $data['role'] = 'resident';
        $data['is_active'] = true;

        return $this->getUserModel()::create($data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->panel->getUrl();
    }
}
