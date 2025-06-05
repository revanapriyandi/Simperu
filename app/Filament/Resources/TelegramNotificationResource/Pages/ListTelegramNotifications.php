<?php

namespace App\Filament\Resources\TelegramNotificationResource\Pages;

use App\Filament\Resources\TelegramNotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTelegramNotifications extends ListRecords
{
    protected static string $resource = TelegramNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
