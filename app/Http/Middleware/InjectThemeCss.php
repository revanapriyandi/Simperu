<?php

namespace App\Http\Middleware;

use App\Services\Theme\ThemeConfigurationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class InjectThemeCss
{
    public function __construct(
        private ThemeConfigurationService $themeService
    ) {}

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only inject CSS for HTML responses
        if (
            $response->headers->get('Content-Type') && 
            strpos($response->headers->get('Content-Type'), 'text/html') !== false
        ) {
            $content = $response->getContent();
            
            // Generate dynamic CSS
            $dynamicCss = Cache::remember('compiled_theme_css', 3600, function () {
                return $this->themeService->generateCssVariables();
            });
            
            // Inject CSS into head
            $cssTag = "<style id=\"dynamic-theme-css\">\n{$dynamicCss}\n</style>";
            
            // Insert before closing head tag
            $content = str_replace('</head>', $cssTag . '</head>', $content);
            
            $response->setContent($content);
        }

        return $response;
    }
}
