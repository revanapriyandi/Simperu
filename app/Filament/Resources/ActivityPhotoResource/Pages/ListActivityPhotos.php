<?php

namespace App\Filament\Resources\ActivityPhotoResource\Pages;

use App\Filament\Resources\ActivityPhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActivityPhotos extends ListRecords
{
    protected static string $resource = ActivityPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
