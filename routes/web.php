<?php

use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->group(function () {
    Route::middleware('guest')->group(function () {
        // LOGIN CONTROLLER
        Route::controller(App\Http\Controllers\Dashboard\Auth\LoginController::class)->group(function () {
            Route::get('/login', 'login')->name('login-index');
            Route::get('/login/request', 'request')->name('login-request');
        });
    });

    Route::middleware('auth')->group(function () {
        // DASHBOARD CONTROLLER
        Route::controller(App\Http\Controllers\Dashboard\DashboardController::class)->group(function () {
            Route::get('/', 'dashboard')->name('dashboard-index');
        });
    });
});

