<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTelegramLinked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip middleware if user is not authenticated
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Skip check for admin role
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Skip if accessing telegram linking routes
        if ($this->isExcludedRoute($request)) {
            return $next($request);
        }        // Check if user has telegram_chat_id
        if (empty($user->telegram_chat_id)) {
            // Store intended URL in session
            session(['telegram_link_intended' => $request->fullUrl()]);

            // If it's an AJAX request, return JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Silakan hubungkan akun Telegram Anda terlebih dahulu.',
                    'telegram_link_url' => route('telegram.link')
                ], 403);
            }

            // Flash message for regular requests
            session()->flash('warning', 'Silakan hubungkan akun Telegram Anda untuk melanjutkan menggunakan sistem ini.');

            return redirect()->route('telegram.link');
        }

        return $next($request);
    }

    /**
     * Check if the current route should be excluded from telegram check
     */
    private function isExcludedRoute(Request $request): bool
    {
        $excludedRoutes = [
            'resident/telegram/*',
            'telegram/*',
            'logout',
            'api/telegram/*',
        ];

        $currentPath = $request->path();

        foreach ($excludedRoutes as $pattern) {
            if (fnmatch($pattern, $currentPath)) {
                return true;
            }
        }

        return false;
    }
}
