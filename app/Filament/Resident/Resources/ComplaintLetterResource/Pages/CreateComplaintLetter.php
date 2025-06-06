<?php

namespace App\Filament\Resident\Resources\ComplaintLetterResource\Pages;

use App\Filament\Resident\Resources\ComplaintLetterResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateComplaintLetter extends CreateRecord
{
    protected static string $resource = ComplaintLetterResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['submitted_by'] = Auth::id();
        $data['submitted_at'] = now();
        $data['status'] = 'submitted';

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
