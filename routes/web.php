<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AuthController;

// 1. Halaman Depan (Welcome)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 2. Authentication Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'processRegister'])->name('register.post');
});

// 3. Route Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 4. Protected Routes (Hanya bisa diakses jika Login)
Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{id}', [ChatController::class, 'index'])->name('chat.show');
    Route::post('/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/new', [ChatController::class, 'newChat'])->name('chat.new');
    Route::put('/session/{id}/rename', [ChatController::class, 'renameSession'])->name('session.rename');
    Route::delete('/session/{id}/delete', [ChatController::class, 'deleteSession'])->name('session.delete');
});
