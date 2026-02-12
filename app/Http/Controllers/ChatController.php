<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index($sessionId = null)
    {
        // Ambil ID User yang sedang login
        $userId = Auth::id();

        // Ambil sesi HANYA milik user ini
        $sessions = Session::where('user_id', $userId)
                           ->orderBy('updated_at', 'desc')
                           ->get();

        $currentSession = null;
        $chats = [];

        if ($sessionId) {
            // Pastikan sesi ini milik user yang login
            $currentSession = Session::where('id', $sessionId)
                                     ->where('user_id', $userId)
                                     ->first();

            if ($currentSession) {
                $chats = $currentSession->chats;
            } else {
                return redirect()->route('chat.index');
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
        $sessionId = $request->session_id;
        $userId = Auth::id();

        // 1. BUAT SESI BARU JIKA BELUM ADA
        if (!$sessionId) {
            $title = Str::words($userMessage, 5, '...');

            // Simpan dengan user_id
            $session = Session::create([
                'user_id' => $userId,
                'title' => $title
            ]);
            $sessionId = $session->id;
        } else {
            // Update timestamp agar naik ke atas
            $session = Session::where('id', $sessionId)->where('user_id', $userId)->first();
            if($session) $session->touch();
        }

        // 2. PANGGIL API GEMINI
        $apiKey = env('GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";

        try {
            $response = Http::withOptions(['verify' => false])
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    "contents" => [
                        ["parts" => [["text" => $userMessage]]]
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $aiReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? "Maaf, saya bingung harus menjawab apa.";
            } elseif ($response->status() == 429) {
                // Pesan khusus jika kena Limit (Error 429)
                $aiReply = "⏳ **Server Sedang Sibuk**\n\nMaaf, Saat ini terlalu banyak permintaan. Mohon tunggu beberapa menit sebelum mengirim pesan lagi ya. Terima kasih atas kesabarannya! 🙏";
            } else {
                // Pesan untuk error lain (Misal API Key salah, atau Google down)
                $aiReply = "⚠️ **Gangguan Sistem**\n\nMaaf, terjadi masalah saat menghubungi AI. Kode Error: " . $response->status();
            }

        } catch (\Exception $e) {
            $aiReply = "🔌 **Koneksi Terputus**\n\nGagal terhubung ke server. Pastikan internet Anda lancar.";
        }

        // 3. SIMPAN CHAT
        Chat::create([
            'session_id' => $sessionId,
            'user_message' => $userMessage,
            'ai_response' => $aiReply
        ]);

        return response()->json([
            'session_id' => $sessionId,
            'user_message' => $userMessage,
            'ai_response' => $aiReply
        ]);
    }

    public function renameSession(Request $request, $id)
    {
        $session = Session::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $session->title = $request->input('title');
        $session->save();
        return response()->json(['success' => true]);
    }

    public function deleteSession($id)
    {
        $session = Session::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $session->delete();
        return response()->json(['success' => true]);
    }

    public function newChat()
    {
        return redirect()->route('chat.index');
    }
}
