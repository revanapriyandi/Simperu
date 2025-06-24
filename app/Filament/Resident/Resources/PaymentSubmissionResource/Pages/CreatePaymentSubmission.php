<?php

namespace App\Filament\Resident\Resources\PaymentSubmissionResource\Pages;

use App\Filament\Resident\Resources\PaymentSubmissionResource;
use App\Models\Family;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CreatePaymentSubmission extends CreateRecord
{
    protected static string $resource = PaymentSubmissionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        $data['submitted_at'] = now();
        $data['status'] = 'pending';
        
        // Auto-fill family_id from user's family
        $userFamily = Family::whereHas(
            'user',
            fn(Builder $q) => $q->where('id', Auth::id())
        )->first();
        
        if ($userFamily) {
            $data['family_id'] = $userFamily->id;
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
