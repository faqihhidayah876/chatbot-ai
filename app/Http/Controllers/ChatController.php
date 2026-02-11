<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::all();
        return view('chat', compact('chats'));
    }

    public function sendMessage(Request $request)
    {
        // Validasi
        $request->validate([
            'message' => 'required'
        ]);

        $userMessage = $request->message;

        // Ambil API Key
        $apiKey = config('services.gemini.key') ?? env('GEMINI_API_KEY');
        $apiKey = trim($apiKey);

        // Payload
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
            // Menggunakan model Gemini terbaru
            $model = 'gemini-2.5-flash';

            $response = Http::withOptions(['verify' => false])
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", $payload);

            if ($response->successful()) {
                $data = $response->json();
                $aiReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? "AI tidak menjawab.";
            } else {
                $errorData = $response->json();
                $pesanError = $errorData['error']['message'] ?? 'Unknown Error';
                $aiReply = "Error Google ({$response->status()}): " . $pesanError;
            }

        } catch (\Exception $e) {
            $aiReply = "Error Sistem: " . $e->getMessage();
        }

        // Simpan ke Database
        Chat::create([
            'user_message' => $userMessage,
            'ai_response' => $aiReply
        ]);

        // --- PERUBAHAN DISINI (AJAX RESPONSE) ---
        // Kita kirim balik data JSON ke JavaScript, bukan reload halaman
        return response()->json([
            'user_message' => $userMessage,
            'ai_response' => $aiReply
        ]);
    }

    public function destroy()
    {
        Chat::truncate();
        return back(); // Kalau hapus chat, boleh reload
    }
}
