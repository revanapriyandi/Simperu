<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Spatie\Color\Hex;
use Filament\PanelProvider;
use Filament\Enums\ThemeMode;
use App\Filament\Pages\Auth\Login;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class ResidentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('resident')
            ->path('resident')
            ->login(\App\Filament\Resident\Pages\Auth\Login::class)
            ->registration(\App\Filament\Resident\Pages\Auth\Register::class)
            ->passwordReset()
            ->colors([
                'primary' => Hex::fromString('#14532d'),
            ])
            ->sidebarWidth('16rem')
            ->brandLogoHeight('5rem')
            ->defaultThemeMode(ThemeMode::Light)
            ->pages([
                \App\Filament\Resident\Pages\Dashboard::class,
            ])
            ->discoverResources(in: app_path('Filament/Resident/Resources'), for: 'App\\Filament\\Resident\\Resources')
            ->discoverPages(in: app_path('Filament/Resident/Pages'), for: 'App\\Filament\\Resident\\Pages')
            ->discoverWidgets(in: app_path('Filament/Resident/Widgets'), for: 'App\\Filament\\Resident\\Widgets')
            ->viteTheme('resources/css/filament/resident/theme.css')
            ->databaseTransactions()
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
