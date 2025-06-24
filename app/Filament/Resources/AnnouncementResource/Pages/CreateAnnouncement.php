<?php

namespace App\Filament\Resources\AnnouncementResource\Pages;

use App\Filament\Resources\AnnouncementResource;
use App\Services\TelegramService;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class CreateAnnouncement extends CreateRecord
{
    protected static string $resource = AnnouncementResource::class;

    protected function afterCreate(): void
    {
        $announcement = $this->record;
        
        // Send to Telegram if enabled
        if ($announcement->send_telegram && $announcement->is_active) {
            try {
                $telegramService = app(TelegramService::class);
                $telegramService->sendAnnouncementToAll($announcement);
                
                Notification::make()
                    ->title('Pengumuman Berhasil Dibuat')
                    ->body('Pengumuman telah dikirim ke Telegram semua warga.')
                    ->success()
                    ->send();
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Pengumuman Dibuat')
                    ->body('Pengumuman berhasil dibuat, namun gagal dikirim ke Telegram: ' . $e->getMessage())
                    ->warning()
                    ->send();
            }
        } else {
            Notification::make()
                ->title('Pengumuman Berhasil Dibuat')
                ->body('Pengumuman telah disimpan dan dipublikasikan.')
                ->success()
                ->send();
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
