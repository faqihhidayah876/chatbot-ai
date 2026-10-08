<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Session;
use App\Models\Chat;
use App\Models\Feedback;
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

    public function analytics()
    {
        // 1. Distribusi mode AI (untuk pie chart)
        $modeDistribution = \App\Models\Chat::query()
            ->whereNotNull('mode')
            ->select('mode', \DB::raw('count(*) as total'))
            ->groupBy('mode')
            ->orderByDesc('total')
            ->get();

        // 2. Top 5 model populer (untuk bar chart)
        $topModels = \App\Models\Chat::query()
            ->whereNotNull('model')
            ->where('created_at', '>=', now()->subDays(30))
            ->select('model', 'provider', \DB::raw('count(*) as total'))
            ->groupBy('model', 'provider')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 3. Aktivitas chat 30 hari terakhir (untuk line chart)
        $dailyActivity = \App\Models\Chat::query()
            ->where('created_at', '>=', now()->subDays(30))
            ->select(
                \DB::raw('DATE(created_at) as date'),
                \DB::raw('count(*) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Isi tanggal yang kosong dengan 0
        $chartLabels = [];
        $chartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('d M');
            $chartData[] = $dailyActivity->get($date)->total ?? 0;
        }

        // 4. Stat cards
        $stats = [
            'chats_today' => \App\Models\Chat::whereDate('created_at', today())->count(),
            'chats_week' => \App\Models\Chat::where('created_at', '>=', now()->startOfWeek())->count(),
            'chats_month' => \App\Models\Chat::where('created_at', '>=', now()->startOfMonth())->count(),
            'active_users_week' => \App\Models\Chat::where('created_at', '>=', now()->subDays(7))
                ->join('chat_sessions', 'chats.session_id', '=', 'chat_sessions.id')
                ->distinct('chat_sessions.user_id')
                ->count('chat_sessions.user_id'),
        ];

        // 5. Top 10 user paling aktif (30 hari terakhir)
        $topUsers = \App\Models\User::query()
            ->where('role', 'user')
            ->join('chat_sessions', 'users.id', '=', 'chat_sessions.user_id')
            ->join('chats', 'chats.session_id', '=', 'chat_sessions.id')
            ->where('chats.created_at', '>=', now()->subDays(30))
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.avatar',
                \DB::raw('count(chats.id) as chat_count')
            )
            ->groupBy('users.id', 'users.name', 'users.email', 'users.avatar')
            ->orderByDesc('chat_count')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'mode_distribution' => $modeDistribution,
            'top_models' => $topModels,
            'daily_activity' => [
                'labels' => $chartLabels,
                'data' => $chartData,
            ],
            'stats' => $stats,
            'top_users' => $topUsers,
        ]);
    }
}
