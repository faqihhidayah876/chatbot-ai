<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::prefix('v1')->name('api.')->group(function () {
    Route::middleware('api_key')->group(function () {
        Route::post('/chat', [ApiController::class, 'chat'])->name('chat');
        Route::get('/me', [ApiController::class, 'me'])->name('me');
    });
});
