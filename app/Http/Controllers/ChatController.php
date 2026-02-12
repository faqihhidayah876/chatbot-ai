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

        // 1. BUAT SESI BARU JIKA BELUM ADA
        if (!$sessionId) {
            $title = Str::words($userMessage, 5, '...');
            $session = Session::create([
                'user_id' => $userId,
                'title' => $title
            ]);
            $sessionId = $session->id;
        } else {
            $session = Session::where('id', $sessionId)->where('user_id', $userId)->first();
            if($session) $session->touch();
        }

        // 2. PANGGIL API NVIDIA NIM
        $apiKey = env('NVIDIA_API_KEY');
        $modelName = env('NVIDIA_MODEL', 'meta/llama3-70b-instruct'); // Fallback jika .env kosong

        // URL Standar NVIDIA NIM (OpenAI Compatible)
        $url = "https://integrate.api.nvidia.com/v1/chat/completions";

        try {
            $response = Http::withOptions(['verify' => false])
                ->withToken($apiKey) // Menggunakan Bearer Token (beda dengan Google yang pakai ?key=)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    "model" => $modelName,
                    "messages" => [
                        [
                            "role" => "user",
                            "content" => $userMessage
                        ]
                    ],
                    "temperature" => 0.5,
                    "top_p" => 1,
                    "max_tokens" => 1024,
                ]);

            // 3. PROSES RESPONSE (Format OpenAI Style)
            if ($response->successful()) {
                $data = $response->json();
                // Perbedaan parsing JSON:
                // Google: ['candidates'][0]['content']['parts'][0]['text']
                // NVIDIA/OpenAI: ['choices'][0]['message']['content']
                $aiReply = $data['choices'][0]['message']['content'] ?? "Maaf, AI tidak memberikan jawaban.";
            } elseif ($response->status() == 429) {
                $aiReply = "⏳ **Server Sibuk**\n\nTerlalu banyak permintaan ke NVIDIA. Mohon tunggu sebentar.";
            } else {
                $aiReply = "⚠️ **Error API**\n\nKode: " . $response->status() . " - " . ($response->json()['error']['message'] ?? 'Unknown Error');
            }

        } catch (\Exception $e) {
            $aiReply = "🔌 **Koneksi Terputus**\n\nGagal menghubungi NVIDIA. Cek internet Anda.";
        }

        // 4. SIMPAN CHAT
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
