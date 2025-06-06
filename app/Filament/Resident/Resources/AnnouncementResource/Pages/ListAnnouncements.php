<?php

namespace App\Filament\Resident\Resources\AnnouncementResource\Pages;

use App\Filament\Resident\Resources\AnnouncementResource;
use Filament\Resources\Pages\ListRecords;

class ListAnnouncements extends ListRecords
{
    protected static string $resource = AnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action for residents
        ];
    }
}
