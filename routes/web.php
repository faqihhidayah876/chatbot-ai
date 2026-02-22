<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminController;

// 1. Halaman Depan (Welcome)
Route::get('/', function () {
    // Jika user sudah login, langsung lempar ke chat (biar gak stuck di welcome)
    if (Auth::check()) {
        return redirect()->route('chat.index');
    }
    return view('welcome');
})->name('home');

// 2. Authentication (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'processRegister'])->name('register.post');
});

// 3. Route Logout (Harus POST demi keamanan)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 4. Protected Routes (Hanya User Login)
Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{id}', [ChatController::class, 'index'])->name('chat.show');
    Route::post('/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/new', [ChatController::class, 'newChat'])->name('chat.new');
    Route::put('/session/{id}/rename', [ChatController::class, 'renameSession'])->name('session.rename');
    Route::delete('/session/{id}/delete', [ChatController::class, 'deleteSession'])->name('session.delete');
});

//khusu admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::delete('/user/{id}', [AdminController::class, 'deleteUser'])->name('deleteUser');
});

// Route untuk membuat link share (Harus login)
Route::post('/session/{id}/share', [App\Http\Controllers\ChatController::class, 'shareSession'])->name('chat.share')->middleware('auth');

// Route untuk melihat chat publik (TIDAK PERLU LOGIN, biar temenmu bisa buka)
Route::get('/share/{token}', [App\Http\Controllers\ChatController::class, 'showPublicSession'])->name('chat.public');
