<?php

namespace App\Filament\Resident\Resources\ComplaintLetterResource\Pages;

use App\Filament\Resident\Resources\ComplaintLetterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComplaintLetter extends EditRecord
{
    protected static string $resource = ComplaintLetterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
