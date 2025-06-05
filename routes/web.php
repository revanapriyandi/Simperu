<?php

use App\Livewire\LandingPage;
use App\Livewire\ViewerMode;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingPage::class)
    ->name('landing-page')
    ->middleware(['web', 'guest']);
