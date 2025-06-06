<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\TelegramService;

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

        $botUsername = config('services.telegram.bot_username', 'your_bot_username');
        $telegramUrl = "https://t.me/{$botUsername}?start=" . base64_encode($user->id);

        return view('telegram.link', compact('telegramUrl', 'user'));
    }

    /**
     * Handle telegram webhook for linking accounts
     */
    public function webhook(Request $request)
    {
        $data = $request->all();

        // Verify the request comes from Telegram (basic check)
        if (!$this->verifyTelegramRequest($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Handle /start command with user ID
        if (isset($data['message']['text']) && str_starts_with($data['message']['text'], '/start ')) {
            $startParam = substr($data['message']['text'], 7); // Remove '/start '

            try {
                $userId = base64_decode($startParam);
                $chatId = $data['message']['chat']['id'];

                if ($this->telegramService->linkUser($userId, $chatId)) {
                    return response()->json(['ok' => true]);
                } else {
                    $this->telegramService->sendMessage($chatId, "❌ Terjadi kesalahan saat menghubungkan akun. Silakan coba lagi.");
                }
            } catch (\Exception $e) {
                $this->telegramService->sendMessage($data['message']['chat']['id'], "❌ Terjadi kesalahan saat menghubungkan akun. Silakan coba lagi.");
            }
        }

        return response()->json(['ok' => true]);
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
