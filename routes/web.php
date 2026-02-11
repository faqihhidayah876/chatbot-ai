<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

// Route::get('/', [ChatController::class, 'index'])->name('chat.index');
// Route::post('/send', [ChatController::class, 'sendMessage'])->name('chat.send');
// Route::delete('/clear', [ChatController::class, 'destroy'])->name('chat.clear');


// Halaman Utama (New Chat)
Route::get('/', [ChatController::class, 'index'])->name('chat.index');

// Membuka History Chat Tertentu (misal: /chat/5)
Route::get('/chat/{id}', [ChatController::class, 'index'])->name('chat.show');

// Kirim Pesan (AJAX)
Route::post('/send', [ChatController::class, 'sendMessage'])->name('chat.send');

// Tombol New Chat di Sidebar
Route::get('/new', [ChatController::class, 'newChat'])->name('chat.new');


// Route untuk Rename & Delete Sesi
Route::put('/session/{id}/rename', [ChatController::class, 'renameSession'])->name('session.rename');
Route::delete('/session/{id}/delete', [ChatController::class, 'deleteSession'])->name('session.delete');
