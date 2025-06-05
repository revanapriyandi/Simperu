<?php

namespace App\Filament\Resources\LandingPageContentResource\Pages;

use App\Filament\Resources\LandingPageContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLandingPageContent extends ViewRecord
{
    protected static string $resource = LandingPageContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
