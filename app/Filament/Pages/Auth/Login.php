<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Page;
use Doctrine\DBAL\Schema\View;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login as BasePage;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BasePage
{
    protected static string $view = 'filament.pages.auth.login';

    public function getSubheading(): string | Htmlable | null
    {
        return __('Silakan login untuk mengakses dashboard');
    }
}
