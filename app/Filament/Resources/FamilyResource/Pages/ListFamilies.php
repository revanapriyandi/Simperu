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
use App\Filament\Pages\ImportFamilies;
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
                ->url(ImportFamilies::getUrl())
                ->color('primary'),
            Actions\Action::make('Export Excel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    return Excel::download(new FamilyExport, 'data_warga.xlsx');
                }),
        ];
    }
}
