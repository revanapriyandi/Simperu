<?php

use App\Livewire\ViewerMode;
use App\Livewire\LandingPage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\ComplaintLetterController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\FamilyTemplateController;

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

// Digital Signature and Letter Management Routes
Route::prefix('letter')->group(function () {
    // Download routes
    Route::get('/resident/{letter}/download', [LetterController::class, 'downloadResidentLetter'])
        ->name('resident.download-letter')
        ->middleware('auth');

    Route::get('/admin/{letter}/download', [LetterController::class, 'downloadAdminLetter'])
        ->name('admin.download-letter')
        ->middleware('auth');

    // Preview routes
    Route::get('/resident/{letter}/preview', [LetterController::class, 'previewResidentLetter'])
        ->name('resident.preview-letter')
        ->middleware('auth');

    Route::get('/admin/{letter}/preview', [LetterController::class, 'previewAdminLetter'])
        ->name('admin.preview-letter')
        ->middleware('auth');

    // Verification routes (public)
    Route::get('/verify/{hash}', [LetterController::class, 'verifySignature'])
        ->name('verify-signature');

    Route::get('/{letter}/qr-verification', [LetterController::class, 'generateVerificationQR'])
        ->name('letter.qr-verification');

    // Admin approval routes
    Route::post('/{letter}/approve', [LetterController::class, 'approveLetter'])
        ->name('letter.approve')
        ->middleware('auth');

    Route::post('/{letter}/reject', [LetterController::class, 'rejectLetter'])
        ->name('letter.reject')
        ->middleware('auth');
});

// Legacy complaint routes (for backward compatibility)
Route::get('/complaint/{complaintLetter}/download-pdf', [ComplaintLetterController::class, 'downloadPdf'])
    ->name('complaint.download-pdf');

Route::get('/complaint/{complaintLetter}/view-pdf', [ComplaintLetterController::class, 'viewPdf'])
    ->name('complaint.view-pdf');

Route::post('/complaint/{complaintLetter}/regenerate-pdf', [ComplaintLetterController::class, 'regeneratePdf'])
    ->name('complaint.regenerate-pdf');

// Families template download route
Route::get('/families/template', [FamilyTemplateController::class, 'download'])->name('families.template');
