<?php

use App\Livewire\LandingPage;
use App\Livewire\ViewerMode;
use App\Http\Controllers\TelegramController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingPage::class)
    ->name('landing-page');

// Telegram routes
Route::prefix('telegram')->group(function () {
    Route::get('/link', [TelegramController::class, 'linkPage'])
        ->name('telegram.link')
        ->middleware('auth');

    Route::post('/webhook', [TelegramController::class, 'webhook'])
        ->name('telegram.webhook');

    Route::get('/status', [TelegramController::class, 'checkStatus'])
        ->name('telegram.status')
        ->middleware('auth');

    Route::post('/unlink', [TelegramController::class, 'unlink'])
        ->name('telegram.unlink')
        ->middleware('auth');
});
