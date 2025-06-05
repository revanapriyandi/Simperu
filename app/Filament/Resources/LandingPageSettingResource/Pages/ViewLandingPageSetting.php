<?php

namespace App\Filament\Resources\LandingPageSettingResource\Pages;

use App\Filament\Resources\LandingPageSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLandingPageSetting extends ViewRecord
{
    protected static string $resource = LandingPageSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
