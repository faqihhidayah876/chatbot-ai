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
        $userId = Auth::id();
        $sessions = Session::where('user_id', $userId)
                           ->orderBy('updated_at', 'desc')
                           ->get();

        $currentSession = null;
        $chats = [];

        if ($sessionId) {
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

        // 1. BUAT/UPDATE SESI
        if (!$sessionId) {
            $title = Str::words($userMessage, 5, '...');
            $session = Session::create(['user_id' => $userId, 'title' => $title]);
            $sessionId = $session->id;
        } else {
            $session = Session::where('id', $sessionId)->where('user_id', $userId)->first();
            if ($session) {
                $session->touch();
            }
        }

        // 2. KONSTRUKSI CHAT (MEMORY + SYSTEM PROMPT)
        $apiKey = env('NVIDIA_API_KEY');
        $modelName = env('NVIDIA_MODEL', 'meta/llama-3.3-70b-instruct');
        $url = "https://integrate.api.nvidia.com/v1/chat/completions";

        // A. System Prompt (Kepribadian AI)
        $systemPrompt = config('sahaja.personality');

        $messages = [
            [
                "role" => "system",
                "content" => $systemPrompt
            ]
        ];

        // B. Context Awareness (Ambil 6 chat terakhir agar AI ingat)
        if ($sessionId) {
            $history = Chat::where('session_id', $sessionId)
                           ->orderBy('created_at', 'desc') // Ambil dari yang terbaru
                           ->take(6) // Batasi 6 chat terakhir (hemat token)
                           ->get()
                           ->reverse(); // Balik urutan jadi kronologis (lama -> baru)

            foreach ($history as $chat) {
                $messages[] = ["role" => "user", "content" => $chat->user_message];
                $messages[] = ["role" => "assistant", "content" => $chat->ai_response];
            }
        }

        // C. Pesan User Sekarang
        $messages[] = ["role" => "user", "content" => $userMessage];

        try {
            $response = Http::withOptions(['verify' => false, 'http_errors' => false])
                ->withToken($apiKey)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    "model" => $modelName,
                    "messages" => $messages, // Kirim array history lengkap
                    "temperature" => 0.5,
                    "top_p" => 1,
                    "max_tokens" => 2048, // Naikkan dikit biar jawaban panjang muat
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $aiReply = $data['choices'][0]['message']['content'] ?? "Maaf, AI tidak memberikan jawaban.";
            } elseif ($response->status() == 429) {
                $aiReply = "⏳ **Server Sibuk**\n\nTerlalu banyak permintaan ke NVIDIA. Mohon tunggu beberapa saat ya 😊";
            } else {
                $aiReply = "⚠️ **Yah, ada kendala teknis nih**\n\nKode: " . $response->status() . " - " . ($response->json()['error']['message'] ?? 'Unknown Error');
            }
        } catch (\Exception $e) {
            $aiReply = "🔌 **Koneksi Terputus**\n\nGagal menghubungi NVIDIA. Cek internet Anda." . $e->getMessage() . "\n\nKalau masalahnya lanjut, kabari SAHAJA Team ya! 😊";
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
