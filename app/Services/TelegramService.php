<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    private string $botToken;
    private string $baseUrl;

    public function __construct()
    {
        $this->botToken = config('services.telegram-bot-api.bot_token');
        $this->baseUrl = "https://api.telegram.org/bot{$this->botToken}";
    }

    /**
     * Send a message to a Telegram chat
     */
    public function sendMessage(string $chatId, string $message, array $options = []): array
    {
        $data = array_merge([
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ], $options);

        try {
            $response = Http::post("{$this->baseUrl}/sendMessage", $data);
            return $response->json() ?? [];
        } catch (\Exception $e) {
            Log::error('Telegram sendMessage failed', [
                'chat_id' => $chatId,
                'error' => $e->getMessage()
            ]);
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Link a user with their Telegram chat ID
     */
    public function linkUser(int $userId, string $chatId): bool
    {
        try {
            $user = User::find($userId);

            if (!$user || $user->role !== 'resident') {
                return false;
            }

            $user->update(['telegram_chat_id' => $chatId]);

            // Send welcome message
            $this->sendMessage(
                $chatId,
                "✅ <b>Akun Telegram Berhasil Terhubung!</b>\n\n" .
                    "Halo <b>{$user->name}</b>,\n\n" .
                    "Akun Telegram Anda telah berhasil dihubungkan dengan sistem SIMPERU. " .
                    "Sekarang Anda akan menerima notifikasi penting melalui Telegram seperti:\n\n" .
                    "• 📢 Pengumuman penting\n" .
                    "• 💰 Konfirmasi pembayaran\n" .
                    "• 📝 Update status surat pengaduan\n" .
                    "• 🔔 Notifikasi sistem lainnya\n\n" .
                    "Terima kasih telah menggunakan SIMPERU! 🏠"
            );

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to link Telegram user', [
                'user_id' => $userId,
                'chat_id' => $chatId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send notification to user via Telegram
     */
    public function sendNotificationToUser(User $user, string $message, array $options = []): bool
    {
        if (empty($user->telegram_chat_id)) {
            return false;
        }

        $result = $this->sendMessage($user->telegram_chat_id, $message, $options);
        return $result['ok'] ?? false;
    }

    /**
     * Send bulk notifications to multiple users
     */
    public function sendBulkNotifications(array $userIds, string $message, array $options = []): array
    {
        $results = [];
        $users = User::whereIn('id', $userIds)
            ->whereNotNull('telegram_chat_id')
            ->where('role', 'resident')
            ->get();

        foreach ($users as $user) {
            $results[$user->id] = $this->sendNotificationToUser($user, $message, $options);
        }

        return $results;
    }

    /**
     * Verify webhook signature (if using secure webhooks)
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $calculatedSignature = hash_hmac('sha256', $payload, $this->botToken);
        return hash_equals($calculatedSignature, $signature);
    }

    /**
     * Set webhook URL for the bot
     */
    public function setWebhook(string $url, array $options = []): array
    {
        $data = array_merge([
            'url' => $url
        ], $options);

        try {
            $response = Http::post("{$this->baseUrl}/setWebhook", $data);
            return $response->json() ?? [];
        } catch (\Exception $e) {
            Log::error('Failed to set Telegram webhook', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get webhook info
     */
    public function getWebhookInfo(): array
    {
        try {
            $response = Http::get("{$this->baseUrl}/getWebhookInfo");
            return $response->json() ?? [];
        } catch (\Exception $e) {
            Log::error('Failed to get Telegram webhook info', [
                'error' => $e->getMessage()
            ]);
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }
}
