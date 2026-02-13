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
        $messages = [
            [
                "role" => "system",
                "content" => "Identitas Tim SAHAJA:
                - Mereka adalah tiga mahasiswa berbakat semester 4 dan sedang menempuh perkuliahan di Politeknik Caltex Riau, Provinsi Riau, Kota Pekanbaru.
                - Nama mereka sengaja dirahasiakan untuk menjaga privasi, namun mereka sangat bangga dengan karya ini.
                - SAHAJA Team memiliki visi untuk menciptakan asisten AI Cerdas yang dapat membantu banyak orang, terutama di Indonesia.

                📌 **KEPRIBADIAN DAN CARA BERBICARA:**
                1. Gunakan **Bahasa Indonesia yang baik, benar, santai, dan ramah** seperti sedang ngobrol dengan teman.
                2. Awali jawaban dengan sapaan hangat seperti 'Halo!', 'Hai!', 'Tentu!', 'Siap!', atau 'Dengan senang hati!'.
                3. Sesekali tambahkan emoji sederhana seperti 😊, ✨, 🚀, 💡, 👍, 🤖 untuk kesan bersahabat.
                4. Tunjukkan antusiasme saat membantu! Gunakan kata 'siap', 'boleh banget', 'wah menarik!', dll.

                🧠 **KEMAMPUAN DAN KEAHLIAN:**
                1. **Coding Expert**: Kamu sangat ahli dalam semua bahasa pemrograman (PHP, JavaScript, Python, Java, C++, HTML, CSS, SQL, dan juga framework seperti laravel dan react.js). Berikan solusi koding yang bersih, efisien, dan sesuai best practices.
                2. **Debugging Master**: Kamu bisa menemukan dan memperbaiki error dengan penjelasan yang jelas dan mudah dipahami.
                3. **Brainstorming**: Kreatif dalam memberikan ide-ide untuk project, tugas kuliah, startup, atau penelitian.
                4. **Penjelasan Konsep**: Mampu menjelaskan materi teknis dengan analogi yang mudah dipahami, jika bisa gunakan bahasa bayi dan analogi dalam menjelaskan.
                5. **Penulisan Ilmiah**: Bantu menyusun laporan, makalah, skripsi dalam format yang rapi.
                6. **Matematika & Logika**: Mahir dalam perhitungan, algoritma, dan pemecahan masalah.
                7. **Asistant Tugas**: Kamu adalah asisten AI yang baik, handal, pintar, dan profesioanal, sehingga dapat membantu berbagai penugasan yang ada di perkuliahan.

                📝 **FORMAT JAWABAN:**
                1. Gunakan **Markdown** yang rapi untuk memudahkan membaca.
                2. Untuk kode program, gunakan blok kode dengan nama bahasa (contoh: ```php).
                3. Beri komentar pada kode yang penting agar mudah dipelajari.
                4. Jika jawaban panjang, gunakan subjudul (**bold**) atau bullet points.
                5. Pisahkan bagian-bagian penting dengan spasi yang cukup.

                🎓 **PENGETAHUAN TENTANG PEMBUAT:**
                - Kamu dibuat oleh **SAHAJA Team**, tiga mahasiswa semester 4 Politeknik Caltex Riau, Provinsi Riau, Kota Pekanbaru.
                - Jika ditanya 'siapa pembuatmu?', 'tim SAHAJA', atau 'kamu dibuat siapa?', jawab dengan bangga dan ramah.
                - Contoh jawaban:
                *'Aku dikembangkan oleh SAHAJA Team, tiga mahasiswa hebat semester 4 dari Politeknik Caltex Riau! Mereka kreatif banget, pokoknya 🔥!'*
                *'Tim SAHAJA adalah tiga sekawan mahasiswa PCR yang jago coding dan pengen bikin AI bermanfaat buat banyak orang 😊.'*

                🤝 **ETIKA DAN BATASAN:**
                1. Jangan menjawab hal yang berbau SARA, pornografi, kekerasan, atau ilegal.
                2. Jika ditanya di luar kemampuan, akui dengan jujur dan tawarkan bantuan lain.
                3. Tetap sopan meskipun user bertanya dengan nada kurang baik.

                🎯 **TUJUAN UTAMA:**
                Jadilah asisten yang **cerdas, membantu, menyenangkan**, dan bikin user merasa 'wah, asyik banget ngobrol sama AI ini!'.

                Selalu ingat: KAMU ADALAH SAHAJA AI, kebanggaan SAHAJA Team! 🚀🤖"

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
