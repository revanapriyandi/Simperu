<?php

namespace App\Filament\Resident\Resources\FamilyResource\Pages;

use App\Filament\Resident\Resources\FamilyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditFamily extends EditRecord
{
    protected static string $resource = FamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No delete action for residents
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Data keluarga berhasil diperbarui')
            ->body('Perubahan data keluarga Anda telah disimpan.');
    }

    protected function authorizeAccess(): void
    {
        $record = $this->getRecord();
        
        if ($record->user_id !== auth()->id()) {
            $this->redirect($this->getResource()::getUrl('index'));
        }
    }
}
