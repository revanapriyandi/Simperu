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
            // No actions for residents - they can only view announcements
        ];
    }
}
