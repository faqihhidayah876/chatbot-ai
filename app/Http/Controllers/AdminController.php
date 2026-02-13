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
        // 1. Ambil semua user kecuali yang sedang login (admin sendiri)
        // withCount akan menghitung jumlah sesi dan chat secara otomatis (Efisien)
        // Asumsi: di Model User sudah ada relasi sessions() dan chats() [Kita cek/tambah nanti]

        // Kita ambil data user biasa saja (role = user)
        $users = User::where('role', 'user')->get();

        // 2. Data Statistik Sederhana
        $totalUsers = User::where('role', 'user')->count();
        $totalSessions = Session::count();
        $totalChats = Chat::count();

        return view('admin.dashboard', compact('users', 'totalUsers', 'totalSessions', 'totalChats'));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Hapus user (Data sesi dan chat akan terhapus otomatis jika setup database cascade benar)
        // Atau kita bisa paksa hapus manual jika perlu, tapi biasanya cascade on delete sudah cukup
        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
}
