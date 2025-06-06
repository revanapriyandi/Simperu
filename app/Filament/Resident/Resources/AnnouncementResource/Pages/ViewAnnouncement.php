<?php

namespace App\Filament\Resident\Resources\AnnouncementResource\Pages;

use App\Filament\Resident\Resources\AnnouncementResource;
use Filament\Resources\Pages\ViewRecord;

class ViewAnnouncement extends ViewRecord
{
    protected static string $resource = AnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No edit/delete actions for residents
        ];
    }
}
