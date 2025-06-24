<?php

namespace App\Filament\Resources\FamilyResource\Pages;

use Filament\Actions;
use App\Exports\FamilyExport;
use App\Imports\FamilyImport;
use Illuminate\Http\UploadedFile;
use Filament\Forms\Components\Html;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\FamilyResource;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Actions\Action;

class ListFamilies extends ListRecords
{
    protected static string $resource = FamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('Import Excel')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('file')
                        ->label('File Excel')
                        ->hintActions([
                            Action::make('Download Template')
                                ->label('Download Template')
                                ->url(route('families.template'))
                        ])
                        ->helperText('Pastikan file yang diunggah sesuai dengan format template yang telah disediakan.')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $file = $data['file'];
                    if ($file instanceof UploadedFile) {
                        try {
                            Excel::import(new FamilyImport, $file);
                            Notification::make()
                                ->title('Import Berhasil')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
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
                }),
            Actions\Action::make('Export Excel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    return Excel::download(new FamilyExport, 'data_warga.xlsx');
                }),
        ];
    }
}
