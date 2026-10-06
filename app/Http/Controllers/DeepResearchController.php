<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeepResearch;
use App\Models\Chat; // <--- WAJIB DITAMBAH
use App\Models\Session; // <--- WAJIB DITAMBAH
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
            Log::error('Deep Research Init Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => Auth::id(),
                'topic'   => $request->topic ?? null,
            ]);
            return response()->json([
                'success' => false, 
                'message' => 'Gagal memulai riset. Silakan coba lagi.'
            ]);
        }
    }

    public function processStep(Request $request)
    {
        set_time_limit(300);
        $research = DeepResearch::findOrFail($request->research_id);
        $cekKepemilikan = \App\Models\Session::where('id', $research->session_id)
                            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                            ->first();

        if (!$cekKepemilikan) {
            abort(403, 'Akses Ditolak: Terdeteksi Percobaan Manipulasi Data!');
        }
        $logs = $research->logs ?? [];

        if ($research->status === 'inisialisasi') {
            $logs[] = ['time' => now()->timezone('Asia/Jakarta')->format('H:i:s'), 'message' => 'Mencari jurnal dan artikel relevan di seluruh internet...'];
            $research->update(['status' => 'mencari_data', 'logs' => $logs]);

            try {
                $response = Http::withOptions(['verify' => config('services.ssl.ca_bundle')])
                    ->timeout(60)
                    ->post('https://api.tavily.com/search', [
                    'api_key' => config('services.tavily.key'),
                    'query' => $research->topic,
                    'search_depth' => 'advanced',
                    'include_answer' => true,
                    'max_results' => 5
                ]);

                if (!$response->successful()) throw new \Exception('Tavily API menolak: ' . $response->body());

                $tavilyData = $response->json();
                $context = "";
                if (isset($tavilyData['results'])) {
                    foreach($tavilyData['results'] as $result) {
                        $context .= "Sumber: " . ($result['title'] ?? 'N/A') . "\nURL: " . ($result['url'] ?? '') . "\nIsi: " . ($result['content'] ?? '') . "\n\n";
                    }
                }

                Cache::put('alpha_context_' . $research->id, $context, 3600);
                $logs[] = ['time' => now()->timezone('Asia/Jakarta')->format('H:i:s'), 'message' => 'Berhasil mengekstrak data, memproses jawaban...'];
                $research->update(['logs' => $logs]);

            } catch (\Exception $e) {
                Log::error('Deep Research Tavily Error', [
                    'message' => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine(),
                    'research_id' => $research->id,
                ]);
                $logs[] = ['time' => now()->timezone('Asia/Jakarta')->format('H:i:s'), 
                           'message' => 'Gagal mengambil data dari internet. Silakan coba lagi.'];
                $research->update(['status' => 'error', 'logs' => $logs]);
            }
            return response()->json(['success' => true, 'status' => $research->status, 'logs' => $logs]);
        }

        elseif ($research->status === 'mencari_data') {
            $logs[] = ['time' => now()->timezone('Asia/Jakarta')->format('H:i:s'), 'message' => 'Membangun logika penalaran dengan mode Alpha...'];
            $research->update(['status' => 'menganalisis', 'logs' => $logs]);

            try {
                $context = Cache::get('alpha_context_' . $research->id, 'Tidak ada data.');

                $systemPrompt = "Anda adalah 'Agen Alpha', pakar peneliti dari SAHAJA AI.
                Tugas Anda: Susun laporan riset yang mendalam dan objektif dalam format Markdown.

                ATURAN WAJIB:
                1. Gunakan Heading (H2, H3) untuk membagi poin pembahasan.
                2. Setiap poin informasi utama WAJIB mencantumkan referensi angka di akhir kalimat, misal: [1], [2].
                3. Di bagian paling bawah, buat bagian khusus bernama '### Daftar Referensi'.
                4. Pada bagian referensi tersebut, Anda WAJIB mencantumkan semua sumber yang digunakan dalam format link Markdown: [Nama Judul Artikel/Jurnal](URL).
                5. Jika URL tersedia, pastikan link tersebut bisa diklik.
                6. Jangan memberikan kalimat 'Agen Alpha, Tanggal: [Tanggal Laporan]' dan juga Agen Alpha '[Tanda Tangan Digital] [Kontak: email@agenalpha.com]'.
                7. SANGAT PENTING: JANGAN membungkus jawaban Anda dengan markdown code block (```markdown ... ```). Langsung tuliskan teks formatnya secara natural!

                DATA WEB UNTUK DIOLAH:
                " . $context;

                $modelAlpha = config('sahaja_ai.models.alpha');
                $endpointAlpha = config('services.mistral.endpoint');
                $providerAlpha = config('sahaja_ai.providers.alpha');

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('services.mistral.key'),
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->withOptions(['verify' => config('services.ssl.ca_bundle')])
                ->timeout(150)
                ->post($endpointAlpha, [
                    'model' => $modelAlpha,
                    'messages' => [['role' => 'user', 'content' => $systemPrompt]],
                    'max_tokens' => 4000,
                    'temperature' => 0.5
                ]);

                // ==========================================================
                // 🚨 JURUS X-RAY ANTI SILENT DEATH (TANGKAP ERROR NVIDIA)
                // ==========================================================
                if (!$response->successful()) {
                    // Jika NVIDIA menolak (Error 400/500), lempar pesan aslinya ke Log!
                    throw new \Exception("NVIDIA API Error: " . $response->body());
                }

                $aiData = $response->json();

                // Pastikan format NVIDIA sesuai ekspektasi
                if (!isset($aiData['choices'][0]['message']['content'])) {
                    throw new \Exception("Format respons aneh dari NVIDIA: " . json_encode($aiData));
                }

                $finalMarkdown = $aiData['choices'][0]['message']['content'];

                $finalMarkdown = trim($finalMarkdown);
                $finalMarkdown = preg_replace('/^```markdown\s*/i', '', $finalMarkdown);
                $finalMarkdown = preg_replace('/^```\s*/', '', $finalMarkdown);
                $finalMarkdown = preg_replace('/```\s*$/', '', $finalMarkdown);
                $finalMarkdown = trim($finalMarkdown);
                // ==========================================================

                // JURUS PAMUNGKAS: Simpan hasil akhir ke tabel CHATS dengan label dinamis
                Chat::create([
                    'session_id' => $research->session_id,
                    'user_message' => "Deep Research: " . $research->topic,
                    'ai_response' => $finalMarkdown,
                    'model_used' => $modelAlpha . ' (' . strtoupper($providerAlpha) . ')'
                ]);

                $logs[] = ['time' => now()->timezone('Asia/Jakarta')->format('H:i:s'), 'message' => 'Analisis selesai. Laporan disimpan ke riwayat chat.'];
                $research->update(['status' => 'selesai', 'logs' => $logs, 'result_markdown' => $finalMarkdown]);
                Cache::forget('alpha_context_' . $research->id);

            } catch (\Exception $e) {
                Log::error('Deep Research AI Error', [
                    'message' => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine(),
                    'research_id' => $research->id,
                    'model' => $modelAlpha ?? 'unknown',
                ]);
                $logs[] = ['time' => now()->timezone('Asia/Jakarta')->format('H:i:s'), 
                           'message' => 'Mesin AI sedang sibuk. Silakan coba lagi.'];
                $research->update(['status' => 'error', 'logs' => $logs]);
            }

            return response()->json(['success' => true, 'status' => $research->status, 'logs' => $logs, 'result' => $research->result_markdown]);
        }

        return response()->json(['success' => true, 'status' => $research->status, 'logs' => $research->logs, 'result' => $research->result_markdown]);
    }
}
