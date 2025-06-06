<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Filament\Notifications\Notification;
use Telegram\Bot\Laravel\Facades\Telegram;
use Filament\Notifications\Actions\Action as NotificationAction;

class TelegramController extends Controller
{
    protected TelegramService $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    /**
     * Show the telegram linking page
     */
    public function linkPage()
    {
        if (!Auth::check()) {
            return redirect()->route('filament.resident.auth.login');
        }

        $user = Auth::user();

        if (!empty($user->telegram_chat_id)) {
            return redirect()->route('filament.resident.pages.dashboard');
        }

        $telegramBotUrl = config('services.telegram-bot-api.bot_url');

        $userTempCode = Str::random(35);
        Cache::store('telegram')
            ->put($userTempCode, Filament::getCurrentPanel()->auth()->user()->id, $seconds = 120);

        $telegramUrl = $telegramBotUrl . '?start=' . $userTempCode;

        Notification::make()
            ->title(__('Notifikasi Telegram'))
            ->body(__('Silakan buka Telegram dan klik tautan di atas untuk mengaktifkan notifikasi.'))
            ->actions([
                NotificationAction::make('open')
                    ->label(__('Buka Telegram'))
                    ->url($telegramUrl)
                    ->openUrlInNewTab()
                    ->icon('tabler-brand-telegram')
                    ->color('success'),
            ])
            ->success()
            ->send();

        return view('telegram.link', compact('telegramUrl', 'user'));
    }

    /**
     * Handle telegram webhook for linking accounts
     */
    public function webhook(Request $request)
    {
        $updates = Telegram::getWebhookUpdate();
        Log::info('Telegram Webhook', [
            'update' => $updates->toArray(),
        ]);

        try {
            $messageText = $updates->getMessage()->getText();
        } catch (Exception $e) {
            return response()->json([
                'code' => $e->getCode(),
                'message' => 'Accepted with error: \'' . $e->getMessage() . '\'',
            ], 202);
        }
        Log::info('Telegram Webhook', [
            'message' => $messageText,
        ]);
        // Check if the message matches the expected pattern.
        if (! Str::of($messageText)->test('/^\/start\s[A-Za-z0-9]{35}$/')) {
            return response('Accepted', 202);
        }

        // Cleanup the string
        $userTempCode = Str::of($messageText)->remove('/start ')->toString();
        Log::info('Telegram Webhook', [
            'user_temp_code' => $userTempCode,
        ]);
        // Get the User ID from the cache using the temp code as key.
        $userId = Cache::store('telegram')->get($userTempCode);
        Log::info('Telegram Webhook', [
            'user_id' => $userId,
        ]);
        $user = User::find($userId);

        // Get Telegram ID from the request.
        $chatId = $request->message['chat']['id'];

        log::info('Telegram Webhook', [
            'user_id' => $userId,
            'chat_id' => $chatId,
        ]);

        // Update user with the Telegram Chat ID
        $user->telegram_chat_id = $chatId;
        $user->save();

        return response('Success', 200);
    }

    /**
     * API endpoint to check if user's telegram is linked
     */
    public function checkStatus()
    {
        if (!Auth::check()) {
            return response()->json(['linked' => false, 'message' => 'Not authenticated'], 401);
        }

        $user = Auth::user();
        $linked = !empty($user->telegram_chat_id);

        return response()->json([
            'linked' => $linked,
            'message' => $linked ? 'Telegram account is linked' : 'Telegram account not linked'
        ]);
    }

    /**
     * Unlink telegram account
     */
    public function unlink()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $user = User::find(Auth::id());
        $user->update(['telegram_chat_id' => null]);

        return response()->json(['success' => true, 'message' => 'Telegram account unlinked successfully']);
    }

    /**
     * Verify that the request comes from Telegram
     */
    private function verifyTelegramRequest(Request $request): bool
    {
        $botToken = config('services.telegram.bot_token');

        if (!$botToken) {
            return false;
        }

        // For basic verification, you might want to implement more robust verification
        // using secret token or IP whitelisting
        return true;
    }
}
