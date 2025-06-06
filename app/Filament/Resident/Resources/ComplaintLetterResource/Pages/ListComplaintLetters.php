<?php

namespace App\Filament\Resident\Resources\ComplaintLetterResource\Pages;

use App\Filament\Resident\Resources\ComplaintLetterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListComplaintLetters extends ListRecords
{
    protected static string $resource = ComplaintLetterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
