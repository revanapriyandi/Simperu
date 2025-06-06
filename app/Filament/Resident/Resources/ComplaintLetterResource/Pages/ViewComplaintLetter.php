<?php

namespace App\Filament\Resident\Resources\ComplaintLetterResource\Pages;

use App\Filament\Resident\Resources\ComplaintLetterResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewComplaintLetter extends ViewRecord
{
    protected static string $resource = ComplaintLetterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(fn($record) => $record->status === 'submitted'),
        ];
    }
}
