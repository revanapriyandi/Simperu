<?php

namespace App\Providers;

use Filament\Pages\Page;
use Illuminate\View\View;
use Filament\Actions\Action;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Pagination\Paginator;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Facades\FilamentView;
use Filament\Support\Facades\FilamentColor;
use Filament\Tables\Actions\Action as TableAction;
use Illuminate\Support\Facades\URL as FacadesURL;
use Soap\Url;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            fn(): View => view('components.auth.login.footer'),
        );

        FilamentIcon::register([
            'panels::sidebar.collapse-button' => 'heroicon-o-bars-3-bottom-right',
            'panels::sidebar.expand-button' => 'heroicon-o-bars-3',
        ]);

        Action::configureUsing(function (Action $action): void {
            $action->modalFooterActionsAlignment(Alignment::End);
        });

        TableAction::configureUsing(function (TableAction $action): void {
            $action->modalFooterActionsAlignment(Alignment::End);
        });

        Page::formActionsAlignment(Alignment::Right);

        $url = parse_url(config('app.url'));
        if ($url['scheme'] == 'https') {
            FacadesURL::forceScheme('https');
        }
    }
}
