<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Chat;
use App\Models\Session;

class ProfileController extends Controller
{
    /**
     * Update profil user (nama & avatar).
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'name' => 'nullable|string|max:255',
                'avatar' => 'nullable|string', // Base64 string dari frontend
            ]);

            $user = User::find(Auth::id());

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan.'
                ], 404);
            }

            if ($request->has('name') && !empty($request->name)) {
                $user->name = trim($request->name);
            }

            if ($request->has('avatar')) {
                // Kalau null → hapus avatar
                // Kalau string base64 → set avatar baru
                $user->avatar = $request->avatar;
            }

            $user->save();

            return response()->json(['success' => true]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Profile update error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan profil. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Hapus semua chat & session user.
     */
    public function clearChats()
    {
        try {
            $userId = Auth::id();

            // Hapus chat dulu (via relasi session)
            Chat::whereHas('session', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->delete();

            // Hapus session
            Session::where('user_id', $userId)->delete();

            Log::info('User cleared all chats', ['user_id' => $userId]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Clear chats error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus riwayat. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Hapus akun user secara permanen.
     */
    public function deleteAccount()
    {
        try {
            $userId = Auth::id();
            $user = User::find($userId);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan.'
                ], 404);
            }

            // Log sebelum hapus
            Log::info('User account deleted', [
                'user_id' => $userId,
                'email' => $user->email,
            ]);

            // Logout dulu
            Auth::logout();

            // Baru hapus user
            // Relasi onDelete('cascade') di migration akan handle 
            // session, chat, feedback, dll
            $user->delete();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Delete account error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus akun. Silakan coba lagi.'
            ], 500);
        }
    }
}
