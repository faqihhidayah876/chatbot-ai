<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Agent\AgentService;
use App\Services\Telegram\TelegramClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller {
    public function __construct(
        protected TelegramClient $tg,
        protected AgentService $agent,
    ) {}

    public function handle(Request $request) {
        // 1. Verifikasi secret token dari Telegram
        $expected = (string) config('telegram.webhook_secret');
        $received = (string) $request->header('X-Telegram-Bot-Api-Secret-Token', '');
        if ($expected === '' || !hash_equals($expected, $received)) {
            Log::warning('Telegram webhook: invalid secret');
            return response()->json(['ok' => false], 403);
        }

        // 2. Handle Callback Query (Klik Tombol)
        if ($request->has('callback_query')) {
            $cb = $request->input('callback_query');
            if (isset($cb['message']['chat']['id'], $cb['id'])) {
                $chatId = (string) $cb['message']['chat']['id'];
                $data = $cb['data'] ?? '';
                $cbId = (string) $cb['id'];

                $this->tg->answerCallbackQuery($cbId, 'Memproses...');

                $user = User::where('telegram_chat_id', $chatId)->first();
                if (!$user) {
                    $this->tg->sendMessage($chatId, "Akun belum terhubung ke SAHAJA AI.\n\nBuka https://sahaja-ai.my.id/profile untuk menghubungkan.\nChat ID Anda: {$chatId}");
                    return response()->json(['ok' => true]);
                }

                $this->tg->sendChatAction($chatId, 'typing');

                // Terjemahkan tombol menjadi prompt untuk AI
                $prompt = match ($data) {
                    'cek_kuota' => 'Tolong cek sisa kuota Cloud Sahaja milik saya dong.',
                    'status_server' => 'Tolong cek sisa hardisk server kita dong.',
                    'bantuan' => 'Tampilkan panduan cara menggunakan SAHAJA Agent.',
                    default => 'Halo',
                };

                try {
                    $reply = $this->agent->chat($user, $prompt);
                } catch (\Throwable $e) {
                    Log::error('Agent callback chat failed', [
                        'user_id' => $user->id,
                        'msg'     => $e->getMessage(),
                    ]);
                    $reply = 'Maaf, ada kesalahan internal. Coba lagi.';
                }

                $this->tg->sendMessage($chatId, $reply);
                return response()->json(['ok' => true]);
            }
        }

        // 3. Handle Pesan Masuk
        $message = $request->input('message');
        if (!is_array($message) || !isset($message['chat']['id'])) {
            return response()->json(['ok' => true]);
        }

        $chatId = (string) $message['chat']['id'];
        $text   = trim((string) ($message['text'] ?? ''));

        // 4. Handle Perintah /start
        if ($text === '/start') {
            $welcomeText = "Halo! Saya **SAHAJA Agent**.\n\n"
                . "Chat ID Anda: `{$chatId}`\n\n"
                . "Silakan pilih menu di bawah ini:";

            $buttons = [
                [
                    ['text' => '📊 Cek Kuota Saya', 'callback_data' => 'cek_kuota'],
                    ['text' => '💻 Status Server', 'callback_data' => 'status_server']
                ],
                [
                    ['text' => '❓ Bantuan', 'callback_data' => 'bantuan']
                ]
            ];

            $this->tg->sendMessageWithInlineKeyboard($chatId, $welcomeText, $buttons);
            return response()->json(['ok' => true]);
        }

        // 5. Handle Upload Dokumen (PDF) atau Foto
        $document = $message['document'] ?? null;
        $photo = $message['photo'] ?? null;

        if ($document || $photo) {
            $user = User::where('telegram_chat_id', $chatId)->first();
            if (!$user) {
                $this->tg->sendMessage($chatId, "Akun Telegram ini belum terhubung ke SAHAJA AI.\n\nBuka https://sahaja-ai.my.id/profile untuk menghubungkan.\nChat ID Anda: {$chatId}");
                return response()->json(['ok' => true]);
            }

            // Ambil info file
            $fileId   = $document ? $document['file_id'] : end($photo)['file_id'];
            $fileName = $document ? ($document['file_name'] ?? 'document.pdf') : ('photo_' . time() . '.jpg');
            $fileSize = $document ? ($document['file_size'] ?? 0) : (end($photo)['file_size'] ?? 0);

            // Batas 20MB (Telegram Bot API limit)
            if ($fileSize > 20 * 1024 * 1024) {
                $this->tg->sendMessage($chatId, '❌ File terlalu besar. Maksimal 20MB via Telegram.');
                return response()->json(['ok' => true]);
            }

            $this->tg->sendMessage($chatId, "⏳ Menerima file `{$fileName}`. Sedang memproses...");
            $this->tg->sendChatAction($chatId, 'upload_document');

            // Download URL file
            $fileUrl = $this->tg->getFileUrl($fileId);
            if (!$fileUrl) {
                $this->tg->sendMessage($chatId, '❌ Gagal mengunduh file dari Telegram.');
                return response()->json(['ok' => true]);
            }

            // Simpan sementara ke storage
            $tempDir = storage_path('app/private/temp_uploads');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0775, true);
            }
            $tempPath = $tempDir . '/' . time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $fileName);

            $fileContent = $this->tg->downloadFile($fileUrl);
            if ($fileContent === null) {
                $fileContent = @file_get_contents($fileUrl);
            }

            if ($fileContent === false || $fileContent === null) {
                $this->tg->sendMessage($chatId, '❌ Gagal mengunduh isi file dari Telegram.');
                return response()->json(['ok' => true]);
            }

            file_put_contents($tempPath, $fileContent);

            // Cek apakah caption/teks meminta ringkasan
            $caption = (string) ($message['caption'] ?? '');
            if (str_contains(strtolower($caption), 'rangkum') || str_contains(strtolower($caption), 'summarize')) {
                if (str_ends_with(strtolower($fileName), '.pdf')) {
                    // Ekstrak teks PDF (menggunakan exec pdftotext)
                    $escapedPath = escapeshellarg($tempPath);
                    $text = shell_exec("pdftotext {$escapedPath} -");

                    if ($text && trim($text) !== '') {
                        $prompt = "Tolong rangkum dokumen PDF berikut ini:\n\n" . substr($text, 0, 15000); // Batasi token
                        $reply = $this->agent->chat($user, $prompt);
                        $this->tg->sendMessage($chatId, $reply);
                    } else {
                        $this->tg->sendMessage($chatId, '❌ Gagal mengekstrak teks dari PDF.');
                    }
                } else {
                    $this->tg->sendMessage($chatId, '❌ Fitur rangkum saat ini hanya untuk file PDF.');
                }
            } else {
                $this->tg->sendMessage($chatId, "✅ File `{$fileName}` berhasil diterima. (Fitur simpan ke Cloud Sahaja akan diimplementasikan di Fase selanjutnya).");
            }

            // Bersihkan file sementara
            @unlink($tempPath);

            return response()->json(['ok' => true]);
        }

        // 6. Jika tidak ada teks biasa dan bukan upload, abaikan
        if ($text === '') {
            return response()->json(['ok' => true]);
        }

        // 7. Identifikasi user untuk chat teks biasa
        $user = User::where('telegram_chat_id', $chatId)->first();
        if (!$user) {
            $this->tg->sendMessage($chatId,
                "Akun Telegram ini belum terhubung ke SAHAJA AI.\n\n"
                . "Buka https://sahaja-ai.my.id/profile untuk menghubungkan.\n\n"
                . "Chat ID Anda: {$chatId}"
            );
            return response()->json(['ok' => true]);
        }

        // 8. Kirim indikator "typing", lalu proses AI
        $this->tg->sendChatAction($chatId, 'typing');

        try {
            $reply = $this->agent->chat($user, $text);
        } catch (\Throwable $e) {
            Log::error('Agent chat failed', [
                'user_id' => $user->id,
                'msg'     => $e->getMessage(),
            ]);
            $reply = 'Maaf, ada kesalahan internal. Coba lagi.';
        }

        $this->tg->sendMessage($chatId, $reply);

        return response()->json(['ok' => true]);
    }
}
