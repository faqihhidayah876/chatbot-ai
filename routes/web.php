<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

Route::get('/', [ChatController::class, 'index'])->name('chat.index');
Route::post('/send', [ChatController::class, 'sendMessage'])->name('chat.send');
Route::delete('/clear', [ChatController::class, 'destroy'])->name('chat.clear');
