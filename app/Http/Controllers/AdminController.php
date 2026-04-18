<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Session;
use App\Models\Chat;
use App\Models\Feedback; // 🌟 Panggil model Feedback baru kita
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Ambil data user beserta detail statistiknya
        $users = User::where('role', 'user')->orderBy('created_at', 'desc')->get()->map(function($user) {
            $sessionIds = Session::where('user_id', $user->id)->pluck('id');
            $user->total_sessions = $sessionIds->count();
            $user->total_chats = Chat::whereIn('session_id', $sessionIds)->count();
            $lastChat = Chat::whereIn('session_id', $sessionIds)->latest()->first();
            $user->last_activity = $lastChat ? $lastChat->created_at->format('d M Y, H:i') : 'Belum ada aktivitas';
            return $user;
        });

        // 2. Data Statistik Global SAHAJA AI
        $totalUsers = User::where('role', 'user')->count();
        $totalSessions = Session::count();
        $totalChats = Chat::count();
        $totalShared = Session::whereNotNull('share_token')->count();

        // 3. AMBIL DATA UMPAN BALIK (Untuk Tab Feedback)
        $feedbacks = Feedback::with('user')->latest()->get();

        // 4. LOGIKA GRAFIK (Melihat Pertumbuhan User 6 Bulan Terakhir)
        $months = [];
        $counts = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M'); // Label: Jan, Feb, Mar, dst.

            // Hitung user yang mendaftar hingga bulan tersebut
            $count = User::where('role', 'user')
                        ->where('created_at', '<=', $date->endOfMonth())
                        ->count();
            $counts[] = $count;
        }

        $chartData = [
            'labels' => $months,
            'data' => $counts
        ];

        return view('admin.dashboard', compact(
            'users',
            'totalUsers',
            'totalSessions',
            'totalChats',
            'totalShared',
            'feedbacks',
            'chartData'
        ));
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
        Chat::whereIn('session_id', $sessionIds)->delete();
        Session::where('user_id', $id)->delete();

        return redirect()->back()->with('success', 'Riwayat obrolan pengguna berhasil dibersihkan.');
    }
}
