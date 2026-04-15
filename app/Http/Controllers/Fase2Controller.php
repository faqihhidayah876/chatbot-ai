<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FasePost;
use App\Models\FaseLike;
use App\Models\FaseComment;
use App\Models\Session;

class Fase2Controller extends Controller
{
    // Tampilkan Halaman Lini Masa
    public function index() {
        // PERBAIKAN: Tambahkan .user pada comments agar tidak error 'on null'
        $posts = FasePost::with(['user', 'likes', 'comments.user'])->latest()->get();

        // Ambil riwayat chat (sessions) untuk Sidebar
        $sessions = Session::where('user_id', Auth::id())->orderBy('updated_at', 'desc')->get();

        return view('on-line', compact('posts', 'sessions'));
    }

    // Simpan Postingan Baru
    public function store(Request $request) {
        $request->validate(['body' => 'required|max:1000']);

        $post = FasePost::create([
            'user_id' => Auth::id(),
            'body' => $request->body
        ]);

        // Kembalikan data untuk dirender JS
        return response()->json([
            'success' => true,
            'post' => $post->load('user')
        ]);
    }

    // Fitur Like / Unlike
    public function toggleLike($id) {
        $userId = Auth::id();
        $like = FaseLike::where('fase_post_id', $id)->where('user_id', $userId)->first();

        if ($like) {
            $like->delete(); // Kalau udah like, berarti unlike
            return response()->json(['status' => 'unliked']);
        } else {
            FaseLike::create(['fase_post_id' => $id, 'user_id' => $userId]); // Kalau belum, like
            return response()->json(['status' => 'liked']);
        }
    }
    // Hapus Postingan Fase 2 (Kebal Error)
    // Hapus Postingan Fase 2 (Kebal Error)
    public function destroy($id) {
        try {
            $post = FasePost::findOrFail($id);

            // Pastikan hanya pemilik postingan yang bisa menghapus
            if ($post->user_id == \Illuminate\Support\Facades\Auth::id()) {

                // HAPUS RELASI MANUAL DULU! (Mencegah SQL Database Error)
                FaseLike::where('fase_post_id', $id)->delete();
                FaseComment::where('fase_post_id', $id)->delete();

                // Baru hapus postingan utamanya
                $post->delete();

                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    // Tambah Komentar Fase 2
    public function addComment(Request $request, $id) {
        $request->validate(['body' => 'required|max:500']);

        FaseComment::create([
            'user_id' => Auth::id(),
            'fase_post_id' => $id,
            'body' => $request->body
        ]);

        return response()->json(['success' => true]);
    }
}
