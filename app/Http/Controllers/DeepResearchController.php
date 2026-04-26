<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeepResearch;
use App\Models\Chat; // <--- WAJIB DITAMBAH
use App\Models\Session; // <--- WAJIB DITAMBAH
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class DeepResearchController extends Controller
{
    public function initResearch(Request $request)
    {
        $request->validate(['topic' => 'required|string', 'session_id' => 'nullable']);

        try {
            $userId = Auth::id();
            $sessionId = empty($request->session_id) ? null : $request->session_id;

            // JURUS ANTI-HILANG: Jika ini chat baru, buatkan session-nya dulu
            if (!$sessionId) {
                $newSession = Session::create([
                    'user_id' => $userId,
                    'title' => substr($request->topic, 0, 50) . '...'
                ]);
                $sessionId = $newSession->id;
            }

            $research = DeepResearch::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'topic' => $request->topic,
                'status' => 'inisialisasi',
                'logs' => [
                    ['time' => now()->timezone('Asia/Jakarta')->format('H:i:s'), 'message' => 'Mengaktifkan Agen Alpha...'],
                    ['time' => now()->timezone('Asia/Jakarta')->format('H:i:s'), 'message' => 'Menyiapkan modul pencarian web untuk: ' . $request->topic]
                ]
            ]);

            return response()->json([
                'success' => true,
                'research_id' => $research->id,
                'session_id' => $sessionId // Kirim ID session agar frontend bisa update URL
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    public function processStep(Request $request)
    {
        $research = DeepResearch::findOrFail($request->research_id);
        $logs = $research->logs ?? [];

        if ($research->status === 'inisialisasi') {
            $logs[] = ['time' => now()->timezone('Asia/Jakarta')->format('H:i:s'), 'message' => 'Mencari jurnal dan artikel relevan di seluruh internet...'];
            $research->update(['status' => 'mencari_data', 'logs' => $logs]);

            try {
                $response = Http::withoutVerifying()->timeout(60)->post('https://api.tavily.com/search', [
                    'api_key' => env('TAVILY_API_KEY'),
                    'query' => $research->topic,
                    'search_depth' => 'advanced',
                    'include_answer' => true,
                    'max_results' => 5
                ]);

                if (!$response->successful()) throw new \Exception('Tavily API error.');

                $tavilyData = $response->json();
                $context = "";
                if (isset($tavilyData['results'])) {
                    foreach($tavilyData['results'] as $result) {
                        $context .= "Sumber: " . ($result['title'] ?? 'N/A') . "\nURL: " . ($result['url'] ?? '') . "\nIsi: " . ($result['content'] ?? '') . "\n\n";
                    }
                }

                Cache::put('alpha_context_' . $research->id, $context, 3600);
                $logs[] = ['time' => now()->timezone('Asia/Jakarta')->format('H:i:s'), 'message' => 'Berhasil mengekstrak sumber data terpercaya.'];
                $research->update(['logs' => $logs]);

            } catch (\Exception $e) {
                $logs[] = ['time' => now()->timezone('Asia/Jakarta')->format('H:i:s'), 'message' => 'Error: ' . $e->getMessage()];
                $research->update(['status' => 'error', 'logs' => $logs]);
            }
            return response()->json(['success' => true, 'status' => $research->status, 'logs' => $logs]);
        }

        elseif ($research->status === 'mencari_data') {
            $logs[] = ['time' => now()->timezone('Asia/Jakarta')->format('H:i:s'), 'message' => 'Membangun logika penalaran dengan model Mistral...'];
            $research->update(['status' => 'menganalisis', 'logs' => $logs]);

            try {
                $context = Cache::get('alpha_context_' . $research->id, 'Tidak ada data.');

                $systemPrompt = "Anda adalah 'Agen Alpha', pakar peneliti dari SAHAJA AI.
                Tugas Anda: Susun laporan riset yang mendalam dan objektif dalam format Markdown.

                ATURAN WAJIB:
                1. Gunakan Heading (H2, H3) untuk membagi poin pembahasan.
                2. Setiap poin informasi utama WAJIB mencantumkan referensi angka di akhir kalimat, misal: [1], [2].
                3. Di bagian paling bawah, buat bagian khusus bernama '### 📚 Daftar Referensi'.
                4. Pada bagian referensi tersebut, Anda WAJIB mencantumkan semua sumber yang digunakan dalam format link Markdown: [Nama Judul Artikel/Jurnal](URL).
                5. Jika URL tersedia, pastikan link tersebut bisa diklik.
                6. Jangan memberikan kalimat 'Agen Alpha, Tanggal: [Tanggal Laporan]' dan juga Agen Alpha '[Tanda Tangan Digital] [Kontak: email@agenalpha.com]'

                DATA WEB UNTUK DIOLAH:
                " . $context;

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('NVIDIA_API_KEY'),
                    'Content-Type' => 'application/json'
                ])->withoutVerifying()->timeout(120)->post('https://integrate.api.nvidia.com/v1/chat/completions', [
                    'model' => 'mistralai/mistral-small-4-119b-2603',
                    'messages' => [['role' => 'user', 'content' => $systemPrompt]],
                    'max_tokens' => 4000,
                    'temperature' => 0.5
                ]);

                $aiData = $response->json();
                $finalMarkdown = $aiData['choices'][0]['message']['content'] ?? 'Gagal.';

                // JURUS PAMUNGKAS: Simpan hasil akhir ke tabel CHATS agar tidak hilang saat refresh!
                Chat::create([
                    'session_id' => $research->session_id,
                    'user_message' => "Deep Research: " . $research->topic,
                    'ai_response' => $finalMarkdown,
                    'model_used' => 'mistralai/mistral-small-4-119b-2603'
                ]);

                $logs[] = ['time' => now()->timezone('Asia/Jakarta')->format('H:i:s'), 'message' => 'Analisis selesai. Laporan disimpan ke riwayat chat.'];
                $research->update(['status' => 'selesai', 'logs' => $logs, 'result_markdown' => $finalMarkdown]);
                Cache::forget('alpha_context_' . $research->id);

            } catch (\Exception $e) {
                $logs[] = ['time' => now()->timezone('Asia/Jakarta')->format('H:i:s'), 'message' => 'Error AI: ' . $e->getMessage()];
                $research->update(['status' => 'error', 'logs' => $logs]);
            }

            return response()->json(['success' => true, 'status' => $research->status, 'logs' => $logs, 'result' => $research->result_markdown]);
        }

        return response()->json(['success' => true, 'status' => $research->status, 'logs' => $research->logs, 'result' => $research->result_markdown]);
    }
}
