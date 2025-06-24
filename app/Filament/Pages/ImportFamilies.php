<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FamilyImport;

class ImportFamilies extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';

    protected static ?string $navigationGroup = 'Data Warga';

    protected static ?string $title = 'Import Data Keluarga';

    protected static ?string $navigationLabel = 'Import Keluarga';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.import-families';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('file')
                    ->label('File Excel')
                    ->required()
                    ->helperText('Gunakan template import yang telah disediakan.')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel']),
            ])
            ->statePath('data');
    }

    public function import(): void
    {
        $file = $this->data['file'] ?? null;

        if ($file instanceof UploadedFile) {
            try {
                Excel::import(new FamilyImport, $file);

                $this->form->fill();

                Notification::make()
                    ->title('Import Berhasil')
                    ->success()
                    ->send();
            } catch (\Throwable $e) {
                Notification::make()
                    ->title('Import Gagal: ' . $e->getMessage())
                    ->danger()
                    ->send();
            }
        } else {
            Notification::make()
                ->title('File tidak valid')
                ->danger()
                ->send();
        }
    }
}
