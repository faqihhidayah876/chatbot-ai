<?php

namespace App\Services\Agent;

use App\Models\AgentMemory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
            . "Gunakan tools yang tersedia jika user menanyakan status server, kuota, daftar file, atau membuat link share Cloud Sahaja.\n"
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
                    'name' => 'get_user_quota',
                    'description' => 'Mengecek sisa kuota penyimpanan Cloud Sahaja milik user yang sedang chat.',
                    'parameters' => ['type' => 'object', 'properties' => (object) []],
                ],
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_server_status',
                    'description' => 'Mengecek sisa kapasitas Hardisk fisik server.',
                    'parameters' => ['type' => 'object', 'properties' => (object) []],
                ],
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'list_files',
                    'description' => 'Menampilkan 5 file terbaru milik user di Cloud Sahaja. Bisa difilter berdasarkan kategori (pdf, image, dll).',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'category' => ['type' => 'string', 'description' => 'Kategori file (opsional)'],
                        ],
                    ],
                ],
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'create_share_link',
                    'description' => 'Membuat link share publik untuk file tertentu di Cloud Sahaja.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'filename' => ['type' => 'string', 'description' => 'Nama file yang ingin di-share'],
                            'expiry' => ['type' => 'string', 'description' => 'Masa aktif link (1h, 1d, 7d, forever)'],
                        ],
                        'required' => ['filename'],
                    ],
                ],
            ],
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
                
                // Parse arguments (handles JSON string or array)
                $rawArgs = $toolCall['function']['arguments'] ?? [];
                $args = is_string($rawArgs) ? (json_decode($rawArgs, true) ?? []) : (array) $rawArgs;

                $toolResult = "";
                if ($functionName === 'get_user_quota') {
                    $toolResult = $this->getUserQuota($user);
                } elseif ($functionName === 'get_server_status') {
                    $toolResult = $this->getServerStatus();
                } elseif ($functionName === 'list_files') {
                    $category = $args['category'] ?? null;
                    $toolResult = $this->getCloudFiles($user, $category);
                } elseif ($functionName === 'create_share_link') {
                    $filename = $args['filename'] ?? '';
                    $expiry = $args['expiry'] ?? '1d';
                    $toolResult = $this->generateShareLink($user, $filename, $expiry);
                }

                if ($toolResult !== "") {
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

    // Fungsi Eksekutor Tool: Kuota Cloud Sahaja User
    protected function getUserQuota(?User $user = null): string {
        if (!$user) {
            return "Informasi user tidak ditemukan.";
        }

        try {
            // Cek ke database Cloud Sahaja menggunakan email user
            $cloudUser = DB::connection('cloud_sahaja')->table('users')
                ->where('email', $user->email)
                ->first();
            
            if ($cloudUser) {
                $used = $cloudUser->storage_used ?? 0;
                $total = $cloudUser->quota_mb ?? 1024; 
                $sisa = $total - $used;
                return "Sisa Kuota Cloud Sahaja milik user ini: {$sisa} MB dari total {$total} MB.";
            }
            return "Akun Cloud Sahaja dengan email {$user->email} belum terdaftar atau tidak ditemukan.";
        } catch (\Throwable $e) {
            Log::error('Cloud DB Error: ' . $e->getMessage());
            return "Gagal terhubung ke database Cloud Sahaja. Pastikan file database dapat diakses.";
        }
    }

    // Fungsi Eksekutor Tool: Status Hardisk Server
    protected function getServerStatus(): string {
        $freeSpaceBytes = @disk_free_space("/") ?: (@disk_free_space(".") ?: 0);
        $freeSpaceMB = round($freeSpaceBytes / 1048576, 2);
        $freeSpaceGB = round($freeSpaceMB / 1024, 2);
        return "Sisa kapasitas Hardisk server: {$freeSpaceMB} MB (~{$freeSpaceGB} GB).";
    }

    // Fungsi Eksekutor Tool: Daftar File Cloud Sahaja User
    protected function getCloudFiles(?User $user, ?string $category): string {
        if (!$user) {
            return "Informasi user tidak ditemukan.";
        }

        try {
            // Asumsi tabel files di Cloud Sahaja. Sesuaikan nama tabel/kolom jika berbeda.
            $query = DB::connection('cloud_sahaja')->table('files')
                ->where('user_id', function($q) use ($user) {
                    $q->select('id')->from('users')->where('email', $user->email);
                })
                ->whereNull('deleted_at'); // Abaikan sampah

            if ($category) {
                $query->where('mime_type', 'LIKE', "%{$category}%");
            }

            $files = $query->orderBy('created_at', 'desc')->limit(5)->get();

            if ($files->isEmpty()) return "Tidak ada file ditemukan.";

            $result = "5 File terbaru Anda:\n";
            foreach ($files as $file) {
                $sizeMb = round(($file->size ?? 0) / 1048576, 2);
                $result .= "- {$file->name} ({$sizeMb} MB)\n";
            }
            return $result;
        } catch (\Throwable $e) {
            Log::error('Cloud DB getCloudFiles error: ' . $e->getMessage());
            return "Gagal mengambil data file dari Cloud Sahaja.";
        }
    }

    // Fungsi Eksekutor Tool: Buat Share Link File
    protected function generateShareLink(?User $user, string $filename, string $expiry): string {
        if (!$user) {
            return "Informasi user tidak ditemukan.";
        }

        try {
            $file = DB::connection('cloud_sahaja')->table('files')
                ->where('user_id', function($q) use ($user) {
                    $q->select('id')->from('users')->where('email', $user->email);
                })
                ->where('name', 'LIKE', "%{$filename}%")
                ->first();

            if (!$file) return "File '{$filename}' tidak ditemukan.";

            // Buat token share (sesuaikan dengan skema tabel share di Cloud Sahaja)
            $token = Str::random(32);
            $expiresAt = null;
            if ($expiry !== 'forever' && !empty($expiry)) {
                try {
                    $expiresAt = now()->add($expiry);
                } catch (\Throwable $te) {
                    $expiresAt = now()->addDay();
                }
            }

            DB::connection('cloud_sahaja')->table('shares')->insert([
                'file_id' => $file->id,
                'token' => $token,
                'expires_at' => $expiresAt,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $url = "https://cloud.sahaja-ai.my.id/share/{$token}";
            return "Link share untuk '{$file->name}': {$url}";
        } catch (\Throwable $e) {
            Log::error('Cloud DB generateShareLink error: ' . $e->getMessage());
            return "Gagal membuat link share.";
        }
    }
}
