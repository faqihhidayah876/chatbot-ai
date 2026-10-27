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

        // 3. Handle Pesan Biasa
        $message = $request->input('message');
        if (!is_array($message) || !isset($message['text'], $message['chat']['id'])) {
            return response()->json(['ok' => true]);
        }

        $chatId = (string) $message['chat']['id'];
        $text   = trim((string) $message['text']);
        if ($text === '') {
            return response()->json(['ok' => true]);
        }

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

        // 5. Identifikasi user
        $user = User::where('telegram_chat_id', $chatId)->first();
        if (!$user) {
            $this->tg->sendMessage($chatId,
                "Akun Telegram ini belum terhubung ke SAHAJA AI.\n\n"
                . "Buka https://sahaja-ai.my.id/profile untuk menghubungkan.\n\n"
                . "Chat ID Anda: {$chatId}"
            );
            return response()->json(['ok' => true]);
        }

        // 6. Kirim indikator "typing", lalu proses AI
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
