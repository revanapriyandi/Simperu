<?php

use App\Livewire\ViewerMode;
use App\Livewire\LandingPage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\ComplaintLetterController;

Route::get('/', LandingPage::class)
    ->name('landing-page');

// Telegram routes
Route::prefix('telegram')->group(function () {
    Route::get('/link', [TelegramController::class, 'linkPage'])
        ->name('telegram.link')
        ->middleware('auth');

    Route::post(
        'telegram/' . config('services.telegram-bot-api.webhook') . '/webhook',
        [TelegramController::class, 'webhook']
    )
        ->name('telegram.webhook')
        ->middleware('auth');

    Route::get('/status', [TelegramController::class, 'checkStatus'])
        ->name('telegram.status')
        ->middleware('auth');

    Route::post('/unlink', [TelegramController::class, 'unlink'])
        ->name('telegram.unlink')
        ->middleware('auth');

    Route::get('/complaint/{complaintLetter}/download-pdf', [ComplaintLetterController::class, 'downloadPdf'])
        ->name('complaint.download-pdf');

    Route::get('/complaint/{complaintLetter}/view-pdf', [ComplaintLetterController::class, 'viewPdf'])
        ->name('complaint.view-pdf');

    Route::post('/complaint/{complaintLetter}/regenerate-pdf', [ComplaintLetterController::class, 'regeneratePdf'])
        ->name('complaint.regenerate-pdf');
});
