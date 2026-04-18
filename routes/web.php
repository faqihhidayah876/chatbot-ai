<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Fase2Controller;

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

// 5. Khusus Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::delete('/user/{id}', [AdminController::class, 'deleteUser'])->name('deleteUser');

    // PERBAIKAN DI SINI: URL jadi '/user/{id}/chats' dan name jadi 'clearChats'
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
    Route::post('/profile/update', function(\Illuminate\Http\Request $request) {
        $user = \App\Models\User::find(\Illuminate\Support\Facades\Auth::id());
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('avatar')) {
            $user->avatar = $request->avatar; // Bisa bernilai null jika dihapus
        }
        $user->save();
        return response()->json(['success' => true]);
    });
    // Hapus Semua Obrolan (Chat & Session)
    Route::delete('/profile/chat/clear', function() {
        $userId = \Illuminate\Support\Facades\Auth::id();
        \App\Models\Chat::whereHas('session', function($q) use ($userId) { $q->where('user_id', $userId); })->delete();
        \App\Models\Session::where('user_id', $userId)->delete();
        return response()->json(['success' => true]);
    });
    // Hapus Akun Permanen
    Route::delete('/profile/account/delete', function() {
        $user = \App\Models\User::find(\Illuminate\Support\Facades\Auth::id());
        $user->delete();
        \Illuminate\Support\Facades\Auth::logout();
        return response()->json(['success' => true]);
    });
});

Route::get('/terms', function () { return view('terms'); })->name('terms');
Route::get('/privacy', function () { return view('privacy'); })->name('privacy');
