<?php

namespace App\Services\Agent;

use App\Models\AgentMemory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AgentService {
    /**
     * Proses 1 pesan masuk → kembalikan teks balasan.
     */
    public function chat(User $user, string $incoming): string {
        $limit   = (int) config('agent.history_limit', 12);
        $history = AgentMemory::where('user_id', $user->id)
            ->orderByDesc('id')
            ->limit($limit)
            ->get()
            ->reverse()
            ->values();

        // Simpan pesan user SEBELUM memanggil AI (agar urutan konsisten)
        AgentMemory::create([
            'user_id' => $user->id,
            'role'    => 'user',
            'content' => $incoming,
        ]);

        $messages = [
            ['role' => 'system', 'content' => $this->systemPrompt($user)],
        ];
        foreach ($history as $h) {
            $messages[] = ['role' => $h->role, 'content' => $h->content];
        }
        $messages[] = ['role' => 'user', 'content' => $incoming];

        $reply = $this->callMistral($messages, $user);

        AgentMemory::create([
            'user_id' => $user->id,
            'role'    => 'assistant',
            'content' => $reply,
        ]);

        return $reply;
    }

    protected function systemPrompt(User $user): string {
        return "Kamu adalah SAHAJA Agent, asisten pribadi di ekosistem SAHAJA AI.\n"
            . "Pemilik akun: {$user->name} ({$user->email}).\n"
            . "Balas dalam Bahasa Indonesia yang ringkas, jelas, dan ramah.\n"
            . "Gunakan tools yang tersedia jika user menanyakan status server atau kuota penyimpanan Cloud Sahaja.\n"
            . "Jika user meminta hal di luar kemampuan tools yang ada, katakan apa adanya — jangan mengarang.";
    }

    protected function callMistral(array $messages, ?User $user = null): string {
        $endpoint = (string) config('agent.mistral.endpoint');
        $key      = (string) config('agent.mistral.api_key');

        if ($key === '') {
            Log::error('AgentService: MISTRAL_API_KEY kosong');
            return 'Maaf, layanan AI belum dikonfigurasi (API key kosong).';
        }

        // 1. Definisikan Tools (Function Calling)
        $tools = [
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_storage_info',
                    'description' => 'Mengecek sisa kapasitas Hardisk Server dan sisa kuota penyimpanan Cloud Sahaja user.',
                    'parameters' => ['type' => 'object', 'properties' => (object) []],
                ],
            ]
        ];

        try {
            $payload = [
                'model'       => config('agent.mistral.model'),
                'messages'    => $messages,
                'max_tokens'  => config('agent.mistral.max_tokens'),
                'temperature' => config('agent.mistral.temperature'),
                'tools'       => $tools,
                'tool_choice' => 'auto'
            ];

            $resp = Http::withOptions([
                'verify' => config('services.ssl.ca_bundle', true),
            ])->timeout(45)->withHeaders([
                'Authorization' => "Bearer {$key}",
                'Content-Type'  => 'application/json',
            ])->post($endpoint, $payload);

            if (!$resp->ok()) {
                Log::error('Mistral error', [
                    'status' => $resp->status(),
                    'body'   => $resp->body(),
                ]);
                return 'Maaf, AI sedang gangguan.';
            }

            $choice = $resp->json('choices.0.message');
            
            // 2. Cek apakah AI meminta eksekusi Tool
            if (isset($choice['tool_calls']) && count($choice['tool_calls']) > 0) {
                $toolCall = $choice['tool_calls'][0];
                $functionName = $toolCall['function']['name'];
                
                $messages[] = $choice; // Wajib menyimpan history tool call
                
                if ($functionName === 'get_storage_info') {
                    $toolResult = $this->getStorageInfo($user);
                    
                    $messages[] = [
                        'role' => 'tool',
                        'tool_call_id' => $toolCall['id'],
                        'name' => $functionName,
                        'content' => $toolResult
                    ];

                    // Panggil Mistral lagi untuk merangkai jawaban akhir
                    return $this->callMistral($messages, $user);
                }
            }

            $content = $choice['content'] ?? '';
            return trim($content) !== '' ? trim($content) : 'Maaf, saya tidak bisa merangkai jawaban.';

        } catch (\Throwable $e) {
            Log::error('Mistral exception', ['msg' => $e->getMessage()]);
            return 'Maaf, terjadi kesalahan saat menghubungi AI.';
        }
    }

    // Fungsi Eksekutor Tool
    protected function getStorageInfo(?User $user = null): string {
        // 1. Cek Sisa HDD Server
        $freeSpaceBytes = @disk_free_space("/") ?: (@disk_free_space(".") ?: 0);
        $freeSpaceMB = round($freeSpaceBytes / 1048576, 2);

        // 2. Cek Kuota Cloud Sahaja
        $cloudQuotaStr = "";
        try {
            $email = $user->email ?? (auth()->user()->email ?? '');
            if (!empty($email)) {
                $cloudUser = DB::connection('cloud_sahaja')->table('users')
                    ->where('email', $email)
                    ->first();
                
                if ($cloudUser && isset($cloudUser->quota_mb)) {
                    $cloudQuotaStr = "Sisa Kuota Cloud Sahaja: {$cloudUser->quota_mb} MB. ";
                }
            }
        } catch (\Throwable $e) {
            // Abaikan jika DB cloud tidak ditemukan
        }

        return "Informasi Server: Sisa HDD kosong adalah {$freeSpaceMB} MB. " . $cloudQuotaStr;
    }
}
