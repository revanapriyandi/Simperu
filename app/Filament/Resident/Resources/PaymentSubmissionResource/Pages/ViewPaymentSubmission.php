<?php

namespace App\Filament\Resident\Resources\PaymentSubmissionResource\Pages;

use App\Filament\Resident\Resources\PaymentSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPaymentSubmission extends ViewRecord
{
    protected static string $resource = PaymentSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(fn($record) => $record->status === 'pending'),
        ];
    }
}
