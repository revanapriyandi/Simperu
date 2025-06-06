<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;

class OptimizeMemoryUsage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set memory limit for the request
        ini_set('memory_limit', '256M');

        // Set execution time limit
        set_time_limit(60);

        // Enable query result caching
        Model::preventLazyLoading(app()->isProduction());

        $response = $next($request);

        // Clear any potential memory leaks
        if (function_exists('gc_collect_cycles')) {
            gc_collect_cycles();
        }

        return $response;
    }
}
