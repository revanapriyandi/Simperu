<?php

namespace App\Filament\Resident\Resources\PaymentSubmissionResource\Pages;

use App\Filament\Resident\Resources\PaymentSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaymentSubmission extends EditRecord
{
    protected static string $resource = PaymentSubmissionResource::class;

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
