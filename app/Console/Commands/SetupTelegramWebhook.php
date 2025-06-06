<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TelegramService;

class SetupTelegramWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:setup-webhook {--url=} {--remove}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup or remove Telegram webhook';

    protected TelegramService $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        parent::__construct();
        $this->telegramService = $telegramService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('remove')) {
            return $this->removeWebhook();
        }

        $url = $this->option('url') ?: config('services.telegram.webhook_url');

        if (!$url) {
            $this->error('Please provide webhook URL using --url option or set TELEGRAM_WEBHOOK_URL in .env');
            return 1;
        }

        $this->info('Setting up Telegram webhook...');
        $this->info("URL: {$url}");

        $result = $this->telegramService->setWebhook($url);

        if ($result['ok'] ?? false) {
            $this->info('✅ Webhook setup successfully!');
            $this->showWebhookInfo();
        } else {
            $this->error('❌ Failed to setup webhook: ' . ($result['description'] ?? 'Unknown error'));
            return 1;
        }

        return 0;
    }

    private function removeWebhook()
    {
        $this->info('Removing Telegram webhook...');

        $result = $this->telegramService->setWebhook('');

        if ($result['ok'] ?? false) {
            $this->info('✅ Webhook removed successfully!');
        } else {
            $this->error('❌ Failed to remove webhook: ' . ($result['description'] ?? 'Unknown error'));
            return 1;
        }

        return 0;
    }

    private function showWebhookInfo()
    {
        $info = $this->telegramService->getWebhookInfo();

        if ($info['ok'] ?? false) {
            $webhook = $info['result'];

            $this->info("\n📋 Webhook Information:");
            $this->info("URL: " . ($webhook['url'] ?: 'Not set'));
            $this->info("Has custom certificate: " . ($webhook['has_custom_certificate'] ? 'Yes' : 'No'));
            $this->info("Pending update count: " . ($webhook['pending_update_count'] ?? 0));

            if (isset($webhook['last_error_date'])) {
                $this->warn("Last error: " . ($webhook['last_error_message'] ?? 'Unknown'));
                $this->warn("Error date: " . date('Y-m-d H:i:s', $webhook['last_error_date']));
            }
        }
    }
}
