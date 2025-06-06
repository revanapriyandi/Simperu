<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post(
    'telegram/' . config('services.telegram-bot-api.webhook') . '/webhook',
    [TelegramController::class, 'webhook']
);
