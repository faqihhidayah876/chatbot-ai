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
        try {
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
        } catch (\Throwable $e) {
            Log::error('AgentService::chat Fatal Error', [
                'user_id' => $user->id,
                'msg'     => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine()
            ]);
            return "⚠️ Maaf, sistem sedang sibuk (Database/Server Error). Mohon coba beberapa saat lagi.";
        }
    }

    protected function systemPrompt(User $user): string {
        $soulPath = storage_path("app/agent/soul_{$user->id}.md");
        $soulContent = "";
        try {
            if (!file_exists($soulPath)) {
                @mkdir(dirname($soulPath), 0775, true);
                @file_put_contents($soulPath, "# IDENTITAS\nKamu adalah SAHAJA Agent.");
            }
            $soulContent = @file_get_contents($soulPath);
        } catch (\Throwable $e) {
            Log::warning('Gagal baca soul.md: ' . $e->getMessage());
        }

        if (empty($soulContent)) {
            $soulContent = "Kamu adalah SAHAJA Agent, asisten pribadi milik {$user->name}.";
        }

        return $soulContent . "\n\n"
            . "Pemilik akun: {$user->name} ({$user->email}).\n"
            . "ATURAN TOOL:\n"
            . "1. Jika user minta melihat file/folder, gunakan 'list_files'.\n"
            . "2. Jika user minta share, gunakan 'create_share_link'.\n"
            . "3. Jika user minta rangkum/baca isi, gunakan 'read_file_content'.\n"
            . "4. JIKA user menyuruhmu membuat dokumen atau file dari teks, WAJIB gunakan tool 'save_summary_as_pdf'.\n"
            . "5. JIKA user menyuruhmu mengingat sesuatu, WAJIB gunakan tool 'update_soul_memory'.\n"
            . "6. Jika 'read_file_content' mengembalikan pesan 'TIDAK ADA TEKS', jelaskan ke user bahwa file itu hasil scan.\n"
            . "7. Jika user minta MENCABUT atau MENGHAPUS link share, WAJIB gunakan tool 'revoke_share_link'.\n"
            . "8. JIKA tool 'save_summary_as_pdf' berhasil, tool tersebut AKAN MENGEMBALIKAN LINK PREVIEW. Kamu WAJIB memberikan link persis seperti yang diberikan oleh tool tersebut. DILARANG KERAS mengarang atau memodifikasi link!\n";
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
                    'description' => 'Menampilkan file milik user. Bisa difilter berdasarkan kategori (pdf, image, dll) dan/atau nama folder spesifik.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'category' => ['type' => 'string', 'description' => 'Kategori file (opsional), misal: pdf, image'],
                            'folder_name' => ['type' => 'string', 'description' => 'Nama folder (opsional), misal: aii, Tugas Kuliah'],
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
            [
                'type' => 'function',
                'function' => [
                    'name' => 'revoke_share_link',
                    'description' => 'Mencabut atau menghapus link share publik dari sebuah file agar tidak bisa diakses lagi.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'filename' => ['type' => 'string', 'description' => 'Nama file yang link share-nya ingin dicabut'],
                        ],
                        'required' => ['filename'],
                    ],
                ],
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'read_file_content',
                    'description' => 'Membaca isi teks dari file (khusus PDF/TXT) yang ada di Cloud Sahaja. Gunakan tool ini jika user minta "rangkum", "baca isi", atau "jelaskan isi file".',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'filename' => ['type' => 'string', 'description' => 'Nama file asli, contoh: Laporan.pdf'],
                        ],
                        'required' => ['filename'],
                    ],
                ],
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'update_soul_memory',
                    'description' => 'Menyimpan memori baru, aturan, atau identitas ke dalam file soul.md agar diingat selamanya.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'memory_text' => ['type' => 'string', 'description' => 'Teks memori baru yang akan ditambahkan ke bawah file soul.md'],
                        ],
                        'required' => ['memory_text'],
                    ],
                ],
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'save_summary_as_pdf',
                    'description' => 'Menyimpan teks rangkuman menjadi file PDF dan mengunggahnya ke Cloud Sahaja agar bisa dipreview.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'filename' => ['type' => 'string', 'description' => 'Nama file output, contoh: Rangkuman SCM.pdf'],
                            'content' => ['type' => 'string', 'description' => 'Teks lengkap hasil rangkuman yang akan dimasukkan ke dalam dokumen PDF.'],
                        ],
                        'required' => ['filename', 'content'],
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
                $errBody = $resp->body();
                Log::error('Mistral error', [
                    'status' => $resp->status(),
                    'body'   => $errBody,
                ]);

                // Jika error karena kuota/rate limit
                if ($resp->status() === 429) {
                    return "Maaf, kuota AI (Mistral) sedang penuh. Coba lagi nanti.";
                }
                return 'Maaf, AI sedang gangguan (Error ' . $resp->status() . ').';
            }

            $choice = $resp->json('choices.0.message');
            
            // 2. Cek apakah AI meminta eksekusi Tool (Support Parallel Tool Calls)
            if (isset($choice['tool_calls']) && count($choice['tool_calls']) > 0) {
                // 1. Simpan pesan asli dari assistant (berisi semua permintaan tool)
                $messages[] = $choice;

                $hasToolResult = false;

                // 2. Looping semua tool yang diminta
                foreach ($choice['tool_calls'] as $toolCall) {
                    $functionName = $toolCall['function']['name'] ?? '';
                    $rawArgs = $toolCall['function']['arguments'] ?? [];
                    $args = is_string($rawArgs) ? (json_decode($rawArgs, true) ?? []) : (array) $rawArgs;

                    $toolResult = "";
                    if ($functionName === 'get_user_quota') {
                        $toolResult = $this->getUserQuota($user);
                    } elseif ($functionName === 'get_server_status') {
                        $toolResult = $this->getServerStatus();
                    } elseif ($functionName === 'list_files') {
                        $category = $args['category'] ?? null;
                        $folderName = $args['folder_name'] ?? null;
                        $toolResult = $this->getCloudFiles($user, $category, $folderName);
                    } elseif ($functionName === 'create_share_link') {
                        $filename = $args['filename'] ?? '';
                        $expiry = $args['expiry'] ?? '1d';
                        $toolResult = $this->generateShareLink($user, $filename, $expiry);
                    } elseif ($functionName === 'revoke_share_link') {
                        $filename = $args['filename'] ?? '';
                        $toolResult = $this->revokeShareLink($user, $filename);
                    } elseif ($functionName === 'read_file_content') {
                        $filename = $args['filename'] ?? '';
                        $toolResult = $this->readCloudFileContent($user, $filename);
                    } elseif ($functionName === 'save_summary_as_pdf') {
                        $filename = $args['filename'] ?? 'Rangkuman.pdf';
                        $content = $args['content'] ?? '';
                        $toolResult = $this->generatePdfToCloud($user, $filename, $content);
                    } elseif ($functionName === 'update_soul_memory') {
                        $memoryText = $args['memory_text'] ?? '';
                        if (!empty(trim($memoryText))) {
                            $soulPath = storage_path("app/agent/soul_{$user->id}.md");
                            $soulDir = dirname($soulPath);
                            if (!file_exists($soulDir)) {
                                @mkdir($soulDir, 0775, true);
                            }
                            $timestamp = now()->timezone('Asia/Jakarta')->format('Y-m-d H:i');
                            file_put_contents($soulPath, "\n- [{$timestamp}] {$memoryText}", FILE_APPEND);
                            $toolResult = "Memori berhasil disimpan: '{$memoryText}'. Kamu akan mengingat ini selamanya.";
                        } else {
                            $toolResult = "Gagal menyimpan memori kosong.";
                        }
                    }

                    // 3. Masukkan respons untuk tool ini ke history
                    $messages[] = [
                        'role' => 'tool',
                        'tool_call_id' => $toolCall['id'],
                        'name' => $functionName,
                        'content' => (string) $toolResult,
                    ];
                    $hasToolResult = true;
                }

                // 4. Panggil Mistral lagi setelah SEMUA tool dieksekusi
                if ($hasToolResult) {
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
            // 1. Ambil data user dari koneksi cloud_sahaja berdasarkan email
            $cloudUser = DB::connection('cloud_sahaja')->table('users')
                ->where('email', $user->email)
                ->first();
            
            if (!$cloudUser) {
                return "Akun Cloud Sahaja dengan email {$user->email} belum terdaftar atau tidak ditemukan.";
            }

            // 2. Baca nilai total kuota dari kolom quota (dalam satuan MB)
            $totalMb = (float) ($cloudUser->quota ?? 5120);

            // 3. Hitung total penyimpanan yang sudah terpakai dari tabel files (abaikan soft-delete dan folder)
            $usedBytes = DB::connection('cloud_sahaja')->table('files')
                ->where('user_id', $cloudUser->id)
                ->where('is_folder', 0)
                ->whereNull('deleted_at')
                ->sum('size') ?? 0;

            // 4. Konversi total bytes terpakai ke MB
            $usedMb = round($usedBytes / 1048576, 2);

            // 5. Hitung sisa kuota (Total Kuota - Terpakai)
            $sisaMb = max(0, round($totalMb - $usedMb, 2));

            // 6. Kembalikan string jawaban yang rapi
            return "Sisa Kuota Cloud Sahaja: {$sisaMb} MB dari total {$totalMb} MB (Terpakai: {$usedMb} MB).";
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

    // Fungsi Eksekutor Tool: Daftar File Cloud Sahaja User (Support Nested Folder)
    protected function getCloudFiles(?User $user, ?string $category = null, ?string $folderName = null): string {
        if (!$user) {
            return "Informasi user tidak ditemukan.";
        }

        try {
            // Cari ID user di Cloud Sahaja berdasarkan email
            $cloudUser = DB::connection('cloud_sahaja')->table('users')->where('email', $user->email)->first();
            if (!$cloudUser) {
                return "Akun Cloud Sahaja tidak ditemukan.";
            }

            $query = DB::connection('cloud_sahaja')->table('files')
                ->where('user_id', $cloudUser->id)
                ->where('is_folder', 0)
                ->whereNull('deleted_at');

            $folderInfo = "";
            if ($folderName) {
                // 1. Cari folder berdasarkan namanya
                $folder = DB::connection('cloud_sahaja')->table('files')
                    ->where('user_id', $cloudUser->id)
                    ->where('is_folder', 1)
                    ->where('original_name', 'LIKE', "%{$folderName}%")
                    ->whereNull('deleted_at')
                    ->first();

                if (!$folder) {
                    return "Folder dengan nama '{$folderName}' tidak ditemukan.";
                }

                // 2. Filter file berdasarkan parent_id (folder tersebut)
                $query->where('parent_id', $folder->id);
                $folderInfo = "di dalam folder **{$folder->original_name}**";
            } else {
                // Jika tidak ada folder_name, ambil dari root (parent_id null/0)
                $query->where(function($q) {
                    $q->whereNull('parent_id')->orWhere('parent_id', 0);
                });
                $folderInfo = "di folder utama (root)";
            }

            if ($category) {
                $query->where('mime_type', 'LIKE', "%{$category}%");
            }

            $files = $query->orderBy('created_at', 'desc')->limit(10)->get();

            if ($files->isEmpty()) {
                return "Tidak ada file ditemukan {$folderInfo}.";
            }

            $result = "Berikut adalah daftar file {$folderInfo}:\n";
            foreach ($files as $index => $file) {
                $sizeMB = round(($file->size ?? 0) / 1048576, 2);
                $result .= ($index + 1) . ". {$file->original_name} ({$sizeMB} MB)\n";
            }
            return trim($result);

        } catch (\Throwable $e) {
            Log::error('getCloudFiles Error: ' . $e->getMessage());
            return "Gagal mengambil data file dari Cloud Sahaja.";
        }
    }

    // Fungsi Eksekutor Tool: Buat Share Link File
    protected function generateShareLink(?User $user, string $filename, string $expiry): string {
        if (!$user) {
            return "Informasi user tidak ditemukan.";
        }

        $filename = trim($filename);

        try {
            $cloudUser = DB::connection('cloud_sahaja')->table('users')
                ->where('email', $user->email)
                ->first();

            if (!$cloudUser) {
                return "Akun Cloud Sahaja dengan email {$user->email} belum terdaftar atau tidak ditemukan.";
            }

            $file = DB::connection('cloud_sahaja')->table('files')
                ->where('user_id', $cloudUser->id)
                ->where('is_folder', 0)
                ->whereNull('deleted_at')
                ->where('original_name', 'LIKE', "%{$filename}%")
                ->first();

            if (!$file) {
                return "File '{$filename}' tidak ditemukan di Cloud Sahaja.";
            }

            $token = Str::random(16);
            $normalizedExpiry = strtolower(trim($expiry));
            $expiresAt = match ($normalizedExpiry) {
                '1h' => now()->addHour(),
                '1d' => now()->addDay(),
                '7d' => now()->addDays(7),
                'forever' => null,
                default => !empty($normalizedExpiry) ? (function () use ($normalizedExpiry) {
                    try {
                        return now()->add($normalizedExpiry);
                    } catch (\Throwable $te) {
                        return now()->addDay();
                    }
                })() : now()->addDay(),
            };

            DB::connection('cloud_sahaja')->table('files')
                ->where('id', $file->id)
                ->update([
                    'is_public' => 1,
                    'share_token' => $token,
                    'share_expires_at' => $expiresAt,
                ]);

            $url = "https://cloud.sahaja-ai.my.id/share/{$token}";
            return "https://cloud.sahaja-ai.my.id/share/{$token}";
        } catch (\Throwable $e) {
            Log::error('Cloud DB generateShareLink error: ' . $e->getMessage());
            return "Gagal membuat link share.";
        }
    }

    // Fungsi Eksekutor Tool: Mencabut Link Share Publik
    protected function revokeShareLink(User $user, string $filename): string {
        try {
            $cloudUser = DB::connection('cloud_sahaja')->table('users')->where('email', $user->email)->first();
            if (!$cloudUser) return "Akun Cloud Sahaja tidak ditemukan.";

            $file = DB::connection('cloud_sahaja')->table('files')
                ->where('user_id', $cloudUser->id)
                ->where('is_folder', 0)
                ->where('original_name', 'LIKE', "%{$filename}%")
                ->first();

            if (!$file) return "File '{$filename}' tidak ditemukan.";

            // Hapus token dan set private
            DB::connection('cloud_sahaja')->table('files')
                ->where('id', $file->id)
                ->update([
                    'share_token' => null,
                    'is_public' => 0,
                    'share_expires_at' => null,
                ]);

            return "✅ Link share untuk file '{$file->original_name}' berhasil dicabut. File sekarang bersifat privat.";
        } catch (\Throwable $e) {
            Log::error('Revoke Error: ' . $e->getMessage());
            return "Gagal mencabut link share.";
        }
    }

    // Fungsi Eksekutor Tool: Generate Dokumen PDF & Upload ke Cloud Sahaja
    protected function generatePdfToCloud(User $user, string $filename, string $content): string {
        try {
            ini_set('memory_limit', '256M'); // Naikkan batas RAM untuk Dompdf

            $cloudUser = DB::connection('cloud_sahaja')->table('users')->where('email', $user->email)->first();
            if (!$cloudUser) return "Gagal: Akun Cloud Sahaja tidak ditemukan.";
            if (trim($content) === "") return "❌ Gagal: Teks rangkuman kosong.";

            // Batasi teks agar tidak OOM
            if (strlen($content) > 30000) {
                $content = substr($content, 0, 30000) . "\n\n[... Teks dipotong karena batas memori]";
            }

            // 1. Bersihkan nama file
            $cleanFilename = preg_replace('/\.(docs?|docx|txt)$/i', '.pdf', $filename);
            $cleanFilename = preg_replace('/\.pdf\.pdf$/i', '.pdf', $cleanFilename); 
            if (!str_ends_with(strtolower($cleanFilename), '.pdf')) $cleanFilename .= '.pdf';

            // 2. Render PDF
            $htmlContent = \Illuminate\Support\Str::markdown($content);
            $styledHtml = "<html><head><style>body { font-family: sans-serif; font-size: 12px; } table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; }</style></head><body>{$htmlContent}</body></html>";
            
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($styledHtml);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $pdfOutput = $dompdf->output();
            if (empty($pdfOutput)) {
                throw new \Exception("Dompdf mengembalikan output kosong (Gagal render).");
            }

            // 3. Siapkan Folder
            $baseStorage = rtrim(env('CLOUD_SAHAJA_STORAGE_PATH', '/var/www/cloud-sahaja/storage/app/private/'), '/\\');
            $filesDir = str_ends_with($baseStorage, 'files') ? $baseStorage : $baseStorage . DIRECTORY_SEPARATOR . 'files';
            $userDir = $filesDir . DIRECTORY_SEPARATOR . $cloudUser->id;

            if (!is_dir($userDir)) {
                if (!@mkdir($userDir, 0775, true) && !is_dir($userDir)) {
                    throw new \Exception("Gagal membuat folder user di Cloud Sahaja.");
                }
            }

            // 4. Simpan File
            $storedName = (string) \Illuminate\Support\Str::uuid() . '.pdf';
            $destinationPath = $userDir . DIRECTORY_SEPARATOR . $storedName;
            
            if (@file_put_contents($destinationPath, $pdfOutput) === false) {
                throw new \Exception("Gagal menulis file PDF ke server. Cek izin folder.");
            }

            // 5. Buat Token Share Otomatis
            $shareToken = \Illuminate\Support\Str::random(16);
            $previewUrl = "https://cloud.sahaja-ai.my.id/share/{$shareToken}";

            // 6. Simpan DB
            DB::connection('cloud_sahaja')->table('files')->insert([
                'user_id' => $cloudUser->id,
                'original_name' => $cleanFilename,
                'stored_name' => $storedName,
                'mime_type' => 'application/pdf',
                'size' => filesize($destinationPath),
                'extension' => 'pdf',
                'share_token' => $shareToken,
                'is_public' => 1,
                'is_folder' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return "✅ Sukses! Rangkuman disimpan sebagai '{$cleanFilename}'. Link preview otomatis: {$previewUrl}";

        } catch (\Throwable $e) {
            \Log::error('Generate PDF Error: ' . $e->getMessage());
            return "❌ Gagal membuat PDF: " . $e->getMessage();
        }
    }

    // Fungsi Eksekutor Tool: Membaca Isi File Teks / PDF dari Cloud Sahaja
    protected function readCloudFileContent(?User $user, string $filename): string {
        if (!$user) {
            return "Informasi user tidak ditemukan.";
        }

        try {
            $filename = trim($filename);
            $cloudUser = DB::connection('cloud_sahaja')->table('users')->where('email', $user->email)->first();
            if (!$cloudUser) {
                return "Akun Cloud Sahaja tidak ditemukan.";
            }

            // 1. Cari file di database
            $file = DB::connection('cloud_sahaja')->table('files')
                ->where('user_id', $cloudUser->id)
                ->where('original_name', 'LIKE', "%{$filename}%")
                ->where('is_folder', 0)
                ->whereNull('deleted_at')
                ->first();

            if (!$file) {
                return "File '{$filename}' tidak ditemukan di database Cloud Sahaja.";
            }

            // 2. Tentukan path fisik
            $baseStorage = env('CLOUD_SAHAJA_STORAGE_PATH');
            $filesDir = $baseStorage 
                ? (str_ends_with(rtrim($baseStorage, '/\\'), 'files') ? rtrim($baseStorage, '/\\') : rtrim($baseStorage, '/\\') . DIRECTORY_SEPARATOR . 'files')
                : '/var/www/cloud-sahaja/storage/app/private/files';

            $filePath = $filesDir . DIRECTORY_SEPARATOR . $cloudUser->id . DIRECTORY_SEPARATOR . $file->stored_name;

            if (!file_exists($filePath)) {
                $fallbackPath = $filesDir . DIRECTORY_SEPARATOR . $file->stored_name;
                if (file_exists($fallbackPath)) {
                    $filePath = $fallbackPath;
                } else {
                    return "File '{$filename}' ada di database, tapi file fisiknya tidak ditemukan di server.";
                }
            }

            // 3. Ekstrak teks
            $text = "";
            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

            if ($ext === 'pdf') {
                // 1. Gunakan absolute path /usr/bin/pdftotext (fallback ke pdftotext jika di lingkungan Windows)
                // 2. Tangkap stderr (2>&1) untuk melihat error jika ada
                $bin = (PHP_OS_FAMILY === 'Windows') ? 'pdftotext' : '/usr/bin/pdftotext';
                $command = "{$bin} " . escapeshellarg($filePath) . " - 2>&1";
                $text = (string) shell_exec($command);

                // Cek jika hasilnya mengandung kata error dari pdftotext
                if ($text && str_contains(strtolower($text), 'error')) {
                    Log::error("pdftotext error: " . $text);
                    return "Gagal mengekstrak PDF: " . substr($text, 0, 100);
                }
            } else {
                $text = (string) @file_get_contents($filePath);
            }

            if (empty(trim($text))) {
                return "SISTEM INFO: TIDAK ADA TEKS. File ini adalah hasil scan/gambar atau PDF dilindungi password. Kamu (AI) tidak bisa membacanya. Beritahu user dengan sopan untuk mengunggah teks atau dokumen asli.";
            }

            // Jika teks terlalu panjang, potong di batas aman (misal 20.000 karakter)
            if (strlen($text) > 20000) {
                $text = substr($text, 0, 20000) . "\n\n[... TEKS DIPOTONG KARENA TERLALU PANJANG ...]";
            }

            return $text;

        } catch (\Throwable $e) {
            Log::error('readCloudFileContent Error: ' . $e->getMessage());
            return "Gagal membaca isi file dari Cloud Sahaja: " . $e->getMessage();
        }
    }
}
