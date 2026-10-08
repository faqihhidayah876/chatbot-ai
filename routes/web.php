<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Fase2Controller;
use App\Http\Controllers\DeepResearchController;
use App\Http\Controllers\SahajaLlmController;
use App\Http\Controllers\ProfileController;

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
    Route::get('/new', [ChatController::class, 'newChat'])->name('chat.new');
    Route::get('/sahaja-llm', [SahajaLlmController::class, 'index'])->name('sahaja-llm.index');

    Route::middleware('throttle:30,1')->group(function () {
        Route::post('/send', [ChatController::class, 'sendMessage'])->name('chat.send');
        Route::post('/deep-research/init', [DeepResearchController::class, 'initResearch'])->name('deep-research.init');
        Route::post('/deep-research/step', [DeepResearchController::class, 'processStep'])->name('deep-research.step');
        Route::post('/sahaja-llm/upload', [SahajaLlmController::class, 'uploadDocument'])->name('sahaja-llm.upload');
    });

    Route::put('/session/{id}/rename', [ChatController::class, 'renameSession'])->name('session.rename');
    Route::delete('/session/{id}/delete', [ChatController::class, 'deleteSession'])->name('session.delete');
    Route::delete('/sahaja-llm/document/{id}', [SahajaLlmController::class, 'deleteDocument'])->name('sahaja-llm.delete');

    // Export routes
    Route::get('/session/{id}/export', [ChatController::class, 'exportSession'])->name('chat.export');
    Route::get('/export/all', [ChatController::class, 'exportAllSessions'])->name('chat.exportAll');
});

// 5. Khusus Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
    Route::delete('/user/{id}', [AdminController::class, 'deleteUser'])->name('deleteUser');
    Route::delete('/user/{id}/chats', [AdminController::class, 'clearUserChats'])->name('clearChats');
});

// 6. Route untuk membuat link share (Harus login)
Route::post('/session/{id}/share', [ChatController::class, 'shareSession'])->name('chat.share')->middleware('auth');

// 7. Route untuk melihat chat publik (TIDAK PERLU LOGIN, biar temenmu bisa buka)
Route::get('/share/{token}', [ChatController::class, 'showPublicSession'])->name('chat.public');

// ROUTE FASE 2
Route::middleware('auth')->group(function () {
    Route::get('/online', [Fase2Controller::class, 'index'])->name('online.index');
    Route::post('/online/post', [Fase2Controller::class, 'store'])->name('online.post');
    Route::post('/online/{id}/like', [Fase2Controller::class, 'toggleLike'])->name('online.like');
    Route::post('/online/{id}/comment', [App\Http\Controllers\Fase2Controller::class, 'addComment'])->name('online.comment');
    Route::delete('/online/{id}/delete', [App\Http\Controllers\Fase2Controller::class, 'destroy'])->name('online.delete');
    Route::post('/feedback/send', [App\Http\Controllers\ChatController::class, 'storeFeedback'])->name('feedback.send');

    // Update Profil (Nama & Foto sekaligus)
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    // Hapus Semua Obrolan (Chat & Session)
    Route::delete('/profile/chat/clear', [ProfileController::class, 'clearChats'])->name('profile.clearChats');
    // Hapus Akun Permanen
    Route::delete('/profile/account/delete', [ProfileController::class, 'deleteAccount'])->name('profile.deleteAccount');
});

Route::get('/terms', function () { return view('terms'); })->name('terms');
Route::get('/privacy', function () { return view('privacy'); })->name('privacy');
Route::get('/offline', function () {
    return view('offline');
})->name('offline');

