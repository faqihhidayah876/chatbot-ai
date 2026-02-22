<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Session;
use App\Models\Chat;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Ambil data user beserta detail statistiknya (Aman dari error relasi)
        $users = User::where('role', 'user')->orderBy('created_at', 'desc')->get()->map(function($user) {
            // Ambil semua ID Sesi milik user ini
            $sessionIds = Session::where('user_id', $user->id)->pluck('id');

            // Hitung total sesi dan total chat
            $user->total_sessions = $sessionIds->count();
            $user->total_chats = Chat::whereIn('session_id', $sessionIds)->count();

            // Cari kapan terakhir kali user ini ngetik prompt
            $lastChat = Chat::whereIn('session_id', $sessionIds)->latest()->first();
            $user->last_activity = $lastChat ? $lastChat->created_at->format('d M Y, H:i') : 'Belum ada aktivitas';

            return $user;
        });

        // 2. Data Statistik Global SAHAJA AI
        $totalUsers = User::where('role', 'user')->count();
        $totalSessions = Session::count();
        $totalChats = Chat::count();
        $totalShared = Session::whereNotNull('share_token')->count(); // Pantau jumlah link publik

        return view('admin.dashboard', compact('users', 'totalUsers', 'totalSessions', 'totalChats', 'totalShared'));
    }

    // FITUR 1: Eksekusi Mati (Hapus Akun User Permanen)
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'Akun pengguna beserta seluruh datanya berhasil dihapus permanen.');
    }

    // FITUR 2: Sapu Bersih (Hanya Hapus Riwayat Chat, Akun Tetap Aman)
    public function clearUserChats($id)
    {
        $sessionIds = Session::where('user_id', $id)->pluck('id');

        // Hapus chat dan sesi
        Chat::whereIn('session_id', $sessionIds)->delete();
        Session::where('user_id', $id)->delete();

        return redirect()->back()->with('success', 'Riwayat obrolan pengguna berhasil dibersihkan.');
    }
}
