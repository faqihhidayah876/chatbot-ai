<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    // Menampilkan halaman utama (Bisa kosong atau membuka chat tertentu)
    public function index(Request $request, $sessionId = null)
    {
        // Ambil semua riwayat sesi untuk sidebar (urutkan dari yang terbaru)
        $sessions = Session::orderBy('created_at', 'desc')->get();

        // Jika ada sessionId, ambil chatnya. Jika tidak, kosong (New Chat).
        $currentSession = null;
        $chats = [];

        if ($sessionId) {
            $currentSession = Session::find($sessionId);
            if ($currentSession) {
                $chats = $currentSession->chats;
            }
        }

        return view('chat', compact('sessions', 'chats', 'currentSession'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required'
        ]);

        $userMessage = $request->message;
        $sessionId = $request->session_id; // ID Sesi dikirim dari JavaScript

        // 1. LOGIKA SESSION
        // Jika belum ada session_id (Chat Baru), buat session baru
        if (!$sessionId) {
            // Judul chat diambil dari 5 kata pertama pesan user
            $title = Str::words($userMessage, 5, '...');
            $session = Session::create(['title' => $title]);
            $sessionId = $session->id;
        }

        // 2. LOGIKA AI (GEMINI)
        $apiKey = config('services.gemini.key') ?? env('GEMINI_API_KEY');
        $apiKey = trim($apiKey);

        $payload = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $userMessage]
                    ]
                ]
            ]
        ];

        try {
            $model = 'gemini-2.5-flash';
            $response = Http::withOptions(['verify' => false])
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", $payload);

            if ($response->successful()) {
                $data = $response->json();
                $aiReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? "AI tidak menjawab.";
            } else {
                $aiReply = "Error API.";
            }
        } catch (\Exception $e) {
            $aiReply = "Error Sistem.";
        }

        // 3. SIMPAN KE DATABASE (Dengan session_id)
        Chat::create([
            'session_id' => $sessionId,
            'user_message' => $userMessage,
            'ai_response' => $aiReply
        ]);

        // 4. Response ke JavaScript
        return response()->json([
            'session_id' => $sessionId, // Penting! Kirim balik ID sesi ke JS
            'session_title' => Str::words($userMessage, 5, '...'),
            'user_message' => $userMessage,
            'ai_response' => $aiReply
        ]);
    }

    // Tombol New Chat (Hanya redirect ke halaman bersih)
    public function newChat()
    {
        return redirect()->route('chat.index');
    }
    // --- FITUR BARU: RENAME & DELETE SESSION ---

    public function renameSession(Request $request, $id)
    {
        $session = Session::findOrFail($id);
        $session->title = $request->input('title');
        $session->save();

        return response()->json(['success' => true]);
    }

    public function deleteSession($id)
    {
        // Hapus sesi (otomatis menghapus chat di dalamnya karena cascade)
        Session::destroy($id);
        return response()->json(['success' => true]);
    }
}
