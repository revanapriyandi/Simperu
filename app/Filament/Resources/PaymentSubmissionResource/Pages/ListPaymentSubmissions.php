<?php

namespace App\Filament\Resources\PaymentSubmissionResource\Pages;

use App\Filament\Resources\PaymentSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaymentSubmissions extends ListRecords
{
    protected static string $resource = PaymentSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
