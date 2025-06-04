<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;

class ContactForm extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                TextInput::make('phone')
                    ->label('Nomor Telepon (Opsional)')
                    ->tel()
                    ->maxLength(20),

                Textarea::make('message')
                    ->label('Pesan Anda')
                    ->required()
                    ->minLength(10)
                    ->rows(4)
                    ->columnSpanFull(),
            ])
            ->statePath('data')
            ->model(null);
    }

    public function sendMessage(): void
    {
        $data = $this->form->getState();

        // Here you can save to database or send email
        // ContactSubmission::create($data);
        // Mail::to('admin@simperu.id')->send(new ContactFormMail($data));

        $this->form->fill();

        Notification::make()
            ->title('Pesan Terkirim!')
            ->body('Terima kasih atas pesan Anda. Kami akan segera menghubungi Anda kembali.')
            ->success()
            ->send();
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
