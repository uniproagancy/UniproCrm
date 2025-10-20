<?php

use Illuminate\Support\Facades\Route;
use App\Events\NewOrderCreated;

Route::prefix('dashboard')->name('dashboard.')->group(function () {



    Route::get('/test-broadcast', function () {
        broadcast(new NewOrderCreated('Hello World!'));
        return 'Broadcast sent!';
    });

    Route::middleware('guest')->group(function () {
        Route::get('/login', App\Livewire\Dashboard\Auth\Login::class)->name('login');
        Route::get('/forgot-password', App\Livewire\Dashboard\Auth\ForgotPassword::class)->name('forgot-password');
        Route::get('/reset-password/{value}', App\Livewire\Dashboard\Auth\ResetPassword::class)->name('reset-password');
        Route::get('/email-verification/{value}', App\Livewire\Dashboard\Auth\EmailVerification::class)->name('email-verification');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/', App\Livewire\Dashboard\Index::class)->name('index');
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/', App\Livewire\Dashboard\Admin\Index::class)->name('index');
            Route::get('/view/{admin_id}', App\Livewire\Dashboard\Admin\View::class)->name('view');
        });

        Route::prefix('meetings')->name('meeting.')->group(function () {
            Route::get('/', App\Livewire\Dashboard\Meeting\Index::class)->name('index');
        });

        Route::prefix('orders')->name('order.')->group(function () {
            Route::get('/', App\Livewire\Dashboard\Order\Index::class)->name('index');
        });
    });

});

Route::prefix('cron')->name('cron.')->group(function () {
    Route::get('birthday', '\App\Http\Controllers\CronControllers\AdminBirthdayController@index')->name('birthday');
});
