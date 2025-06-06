<?php

namespace App\Filament\Resident\Resources\PaymentSubmissionResource\Pages;

use App\Filament\Resident\Resources\PaymentSubmissionResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePaymentSubmission extends CreateRecord
{
    protected static string $resource = PaymentSubmissionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['submitted_by'] = Auth::id();
        $data['submitted_at'] = now();
        $data['status'] = 'pending';

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
