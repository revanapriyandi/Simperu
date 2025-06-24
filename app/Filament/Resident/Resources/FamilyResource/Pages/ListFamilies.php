<?php

namespace App\Filament\Resident\Resources\FamilyResource\Pages;

use App\Filament\Resident\Resources\FamilyResource;
use Filament\Resources\Pages\ListRecords;

class ListFamilies extends ListRecords
{
    protected static string $resource = FamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action for residents
        ];
    }
}
