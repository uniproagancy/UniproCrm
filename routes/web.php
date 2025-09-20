<?php

use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->name('dashboard.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/login', App\Livewire\Dashboard\Auth\Login::class)->name('login');
        Route::get('/forgot-password', App\Livewire\Dashboard\Auth\ForgotPassword::class)->name('forgot-password');
        Route::get('/reset-password/{value}', App\Livewire\Dashboard\Auth\ResetPassword::class)->name('reset-password');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/', App\Livewire\Dashboard\Index::class)->name('index');
    });
});

