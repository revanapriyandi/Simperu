<?php

namespace App\Filament\Resources\AnnouncementResource\Pages;

use App\Filament\Resources\AnnouncementResource;
use App\Services\TelegramService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditAnnouncement extends EditRecord
{
    protected static string $resource = AnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('sendToTelegram')
                ->label('Kirim ke Telegram')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->visible(fn () => $this->record->is_active)
                ->requiresConfirmation()
                ->modalHeading('Kirim Ulang ke Telegram')
                ->modalDescription('Apakah Anda yakin ingin mengirim pengumuman ini ke Telegram semua warga?')
                ->action(function () {
                    try {
                        $telegramService = app(TelegramService::class);
                        $telegramService->sendAnnouncementToAll($this->record);
                        
                        Notification::make()
                            ->title('Berhasil Dikirim')
                            ->body('Pengumuman telah dikirim ke Telegram semua warga.')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal Dikirim')
                            ->body('Gagal mengirim ke Telegram: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Jika send_telegram diaktifkan saat edit dan belum pernah dikirim
        if ($this->record->send_telegram && $this->record->is_active && !$this->record->telegram_sent_at) {
            try {
                $telegramService = app(TelegramService::class);
                $telegramService->sendAnnouncementToAll($this->record);
                
                Notification::make()
                    ->title('Pengumuman Diperbarui')
                    ->body('Pengumuman telah diperbarui dan dikirim ke Telegram.')
                    ->success()
                    ->send();
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Pengumuman Diperbarui')
                    ->body('Pengumuman berhasil diperbarui, namun gagal dikirim ke Telegram: ' . $e->getMessage())
                    ->warning()
                    ->send();
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
