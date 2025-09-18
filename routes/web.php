<?php

use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->group(function () {

    Route::middleware('guest')->group(function () {

        // LOGIN CONTROLLER
        Route::get('/login', App\Livewire\Dashboard\Auth\Login::class)->name('login-index');

        // FORGOT PASSWORD CONTROLLER
        Route::controller(App\Http\Controllers\Dashboard\Auth\ForgotPasswordController::class)->group(function () {
            Route::get('/forgot-password', 'forgot')->name('forgot-index');
        });

        // RESET PASSWORD CONTROLLER
        Route::controller(App\Http\Controllers\Dashboard\Auth\ResetPasswordController::class)->group(function () {
            Route::get('/reset-password', 'reset')->name('reset-index');
        });
    });

    Route::middleware('auth')->group(function () {
        // DASHBOARD CONTROLLER
        Route::controller(App\Http\Controllers\Dashboard\DashboardController::class)->group(function () {
            Route::get('/', 'index')->name('dashboard-index');
        });
    });

});

