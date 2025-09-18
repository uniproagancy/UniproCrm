<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(\App\Http\Controllers\ApiControllers\SSApiController::class)->prefix('ss')->group(function () {
     Route::get('/get-request', 'getRequest');
     Route::post('/upload-application', 'uploadSSAplication');
     Route::post('/update-application', 'updateSSAplication');
     Route::post('/delete-application', 'deleteSSAplication');
     Route::post('/update-expired-application', 'updateExpiredSSAplication');
});
