<?php

namespace App\Services;

use App\Models\TelegramNotification;
use App\Models\User;
use App\Models\Announcement;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected string $botToken;
    protected string $baseUrl;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token');
        $this->baseUrl = "https://api.telegram.org/bot{$this->botToken}";
    }

    /**
     * Send message to specific chat ID
     */
    public function sendMessage(string $chatId, string $message, array $options = []): bool
    {
        try {
            $response = Http::post("{$this->baseUrl}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => $options['parse_mode'] ?? 'HTML',
                'disable_web_page_preview' => $options['disable_preview'] ?? true,
            ]);

            if ($response->successful()) {
                Log::info("Telegram message sent to {$chatId}");
                return true;
            } else {
                Log::error("Failed to send Telegram message to {$chatId}: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Telegram API error for {$chatId}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send announcement to all active residents with Telegram
     */
    public function sendAnnouncementToAll(Announcement $announcement): void
    {
        if (!$announcement->send_telegram) {
            return;
        }

        $users = User::where('role', 'resident')
            ->where('is_active', true)
            ->whereNotNull('telegram_chat_id')
            ->get();

        foreach ($users as $user) {
            $this->sendAnnouncementToUser($announcement, $user);
        }

        // Mark announcement as sent
        $announcement->update(['telegram_sent_at' => now()]);
    }

    /**
     * Send announcement to specific user
     */
    public function sendAnnouncementToUser(Announcement $announcement, User $user): void
    {
        $message = $this->formatAnnouncementMessage($announcement);
        
        $notification = TelegramNotification::create([
            'type' => 'announcement',
            'reference_id' => $announcement->id,
            'message' => $message,
            'status' => 'pending',
        ]);

        $success = $this->sendMessage($user->telegram_chat_id, $message);

        if ($success) {
            $notification->markAsSent();
        } else {
            $notification->markAsFailed('Failed to send via Telegram API');
        }
    }

    /**
     * Format announcement message for Telegram
     */
    protected function formatAnnouncementMessage(Announcement $announcement): string
    {
        $typeEmoji = match ($announcement->type) {
            'urgent' => '🚨',
            'info' => 'ℹ️',
            'event' => '📅',
            'financial' => '💰',
            default => '📢',
        };

        $message = "{$typeEmoji} <b>PENGUMUMAN - VILLA WINDARO PERMAI</b>\n\n";
        $message .= "<b>{$announcement->title}</b>\n\n";
        $message .= $this->formatTextForTelegram($announcement->content);
        
        if ($announcement->expire_date) {
            $message .= "\n\n⏰ <i>Berlaku hingga: " . $announcement->expire_date->format('d/m/Y H:i') . "</i>";
        }

        $message .= "\n\n📱 <i>Pesan otomatis dari Sistem Informasi Villa Windaro Permai</i>";

        return $message;
    }

    /**
     * Format text for Telegram HTML parse mode
     */
    protected function formatTextForTelegram(string $text): string
    {
        // Convert basic formatting
        $text = str_replace(['<strong>', '</strong>'], ['<b>', '</b>'], $text);
        $text = str_replace(['<em>', '</em>'], ['<i>', '</i>'], $text);
        
        // Remove unsupported HTML tags
        $text = strip_tags($text, '<b><i><u><s><code><pre><a>');
        
        return $text;
    }

    /**
     * Send notification for new complaint letter
     */
    public function sendComplaintNotification(string $letterNumber, string $status): void
    {
        $admins = User::where('role', 'admin')
            ->where('is_active', true)
            ->whereNotNull('telegram_chat_id')
            ->get();

        $message = "🔔 <b>PENGADUAN BARU</b>\n\n";
        $message .= "Nomor Surat: <code>{$letterNumber}</code>\n";
        $message .= "Status: <b>{$status}</b>\n\n";
        $message .= "Silakan cek sistem untuk detail lengkap.";

        foreach ($admins as $admin) {
            $notification = TelegramNotification::create([
                'type' => 'complaint',
                'reference_id' => null,
                'message' => $message,
                'status' => 'pending',
            ]);

            $success = $this->sendMessage($admin->telegram_chat_id, $message);

            if ($success) {
                $notification->markAsSent();
            } else {
                $notification->markAsFailed('Failed to send via Telegram API');
            }
        }
    }

    /**
     * Send payment submission notification
     */
    public function sendPaymentNotification(string $submissionId, string $amount, string $type): void
    {
        $admins = User::where('role', 'admin')
            ->where('is_active', true)
            ->whereNotNull('telegram_chat_id')
            ->get();

        $message = "💰 <b>PEMBAYARAN BARU</b>\n\n";
        $message .= "ID: <code>{$submissionId}</code>\n";
        $message .= "Jenis: <b>{$type}</b>\n";
        $message .= "Jumlah: <b>Rp " . number_format($amount, 0, ',', '.') . "</b>\n\n";
        $message .= "Silakan verifikasi pembayaran di sistem.";

        foreach ($admins as $admin) {
            $notification = TelegramNotification::create([
                'type' => 'payment',
                'reference_id' => $submissionId,
                'message' => $message,
                'status' => 'pending',
            ]);

            $success = $this->sendMessage($admin->telegram_chat_id, $message);

            if ($success) {
                $notification->markAsSent();
            } else {
                $notification->markAsFailed('Failed to send via Telegram API');
            }
        }
    }

    /**
     * Test telegram connection
     */
    public function testConnection(): array
    {
        try {
            $response = Http::get("{$this->baseUrl}/getMe");
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Bot token invalid or connection failed',
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
