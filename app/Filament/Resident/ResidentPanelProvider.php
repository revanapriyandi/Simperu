<?php

namespace App\Filament\Resident;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ResidentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('resident')
            ->path('/resident')
            ->login()
            ->brandName('Villa Windaro Permai')
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('images/favicon.ico'))
            ->colors([
                'primary' => Color::Blue,
                'gray' => Color::Slate,
                'success' => Color::Green,
                'warning' => Color::Amber,
                'danger' => Color::Red,
                'info' => Color::Sky,
            ])
            ->font('Inter')
            ->darkMode()
            ->topNavigation(false)
            ->sidebarWidth('280px')
            ->discoverResources(in: app_path('Filament/Resident/Resources'), for: 'App\\Filament\\Resident\\Resources')
            ->discoverPages(in: app_path('Filament/Resident/Pages'), for: 'App\\Filament\\Resident\\Pages')
            ->discoverWidgets(in: app_path('Filament/Resident/Widgets'), for: 'App\\Filament\\Resident\\Widgets')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                Widgets\AccountWidget::class,
            ])
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
            ])
            ->viteTheme('resources/css/filament/resident/theme.css')
            ->navigationGroups([
                'Dashboard' => [
                    'sort' => 1,
                    'icon' => 'heroicon-o-home',
                    'collapsible' => false,
                ],
                'Layanan Surat' => [
                    'sort' => 2,
                    'icon' => 'heroicon-o-document-text',
                    'collapsible' => true,
                ],
                'Administrasi Keuangan' => [
                    'sort' => 3,
                    'icon' => 'heroicon-o-credit-card',
                    'collapsible' => true,
                ],
                'Informasi & Komunikasi' => [
                    'sort' => 4,
                    'icon' => 'heroicon-o-megaphone',
                    'collapsible' => true,
                ],
                'Data & Profil' => [
                    'sort' => 5,
                    'icon' => 'heroicon-o-user-circle',
                    'collapsible' => true,
                ],
            ])
            ->sidebarCollapsibleOnDesktop()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->spa()
            ->unsavedChangesAlerts()
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->breadcrumbs(false)
            ->maxContentWidth('full')
            ->navigationItems([
                // Dashboard items will be automatically added
            ])
            ->userMenuItems([
                'profile' => \Filament\Pages\Auth\EditProfile::class,
                'logout' => \Filament\Http\Middleware\Authenticate::class,
            ])
            ->globalSearch(true)
            ->globalSearchDebounce('500ms');
    }
}
