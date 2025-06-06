<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PerformanceMonitorService
{
    public static function cacheStats($key, $callback, $minutes = 5)
    {
        return Cache::remember($key, now()->addMinutes($minutes), $callback);
    }

    public static function logSlowQuery($query, $time)
    {
        if ($time > 1000) { // Log queries taking more than 1 second
            Log::warning('Slow Query Detected', [
                'query' => $query,
                'time' => $time . 'ms',
                'stack_trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5)
            ]);
        }
    }

    public static function optimizeResidentStatsQuery($userId)
    {
        return self::cacheStats("resident_stats_{$userId}", function () use ($userId) {
            // Get user's family ID efficiently
            $familyId = DB::table('families')
                ->where('user_id', $userId)
                ->value('id');

            if (!$familyId) {
                return [
                    'pending_payments' => 0,
                    'approved_payments' => 0,
                    'pending_complaints' => 0,
                    'resolved_complaints' => 0,
                    'active_announcements' => 0,
                ];
            }

            // Get payment stats with single query
            $paymentStats = DB::table('payment_submissions')
                ->select(DB::raw('
                    SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_payments,
                    SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved_payments
                '))
                ->where('family_id', $familyId)
                ->first();

            // Get complaint stats with single query
            $complaintStats = DB::table('complaint_letters')
                ->select(DB::raw('
                    SUM(CASE WHEN status IN ("submitted", "in_review") THEN 1 ELSE 0 END) as pending_complaints,
                    SUM(CASE WHEN status = "resolved" THEN 1 ELSE 0 END) as resolved_complaints
                '))
                ->where('user_id', $userId)
                ->first();

            // Get active announcements count with limit
            $activeAnnouncements = DB::table('announcements')
                ->where('is_active', true)
                ->where('publish_date', '<=', now())
                ->limit(100)
                ->count();

            return [
                'pending_payments' => $paymentStats->pending_payments ?? 0,
                'approved_payments' => $paymentStats->approved_payments ?? 0,
                'pending_complaints' => $complaintStats->pending_complaints ?? 0,
                'resolved_complaints' => $complaintStats->resolved_complaints ?? 0,
                'active_announcements' => $activeAnnouncements,
            ];
        }, 10); // Cache for 10 minutes
    }

    public static function getLatestAnnouncementsOptimized()
    {
        return self::cacheStats('latest_announcements', function () {
            return DB::table('announcements')
                ->where('is_active', true)
                ->where('publish_date', '<=', now())
                ->orderBy('publish_date', 'desc')
                ->limit(10)
                ->get();
        }, 15); // Cache for 15 minutes
    }

    public static function clearUserCache($userId)
    {
        Cache::forget("resident_stats_{$userId}");
    }

    public static function clearAnnouncementsCache()
    {
        Cache::forget('latest_announcements');
    }
}
