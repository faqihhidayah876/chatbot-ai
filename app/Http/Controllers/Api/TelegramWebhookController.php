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

        // 2. Ambil payload message
        $message = $request->input('message');
        if (!is_array($message) || !isset($message['text'], $message['chat']['id'])) {
            return response()->json(['ok' => true]);
        }

        $chatId = (string) $message['chat']['id'];
        $text   = trim((string) $message['text']);
        if ($text === '') {
            return response()->json(['ok' => true]);
        }

        // 3. Perintah /start
        if ($text === '/start') {
            $this->tg->sendMessage($chatId,
                "Halo! Saya SAHAJA Agent.\n\n"
                . "Untuk mulai, hubungkan akun Telegram Anda di profil SAHAJA AI:\n"
                . "https://sahaja-ai.my.id/profile\n\n"
                . "Chat ID Anda: {$chatId}"
            );
            return response()->json(['ok' => true]);
        }

        // 4. Identifikasi user
        $user = User::where('telegram_chat_id', $chatId)->first();
        if (!$user) {
            $this->tg->sendMessage($chatId,
                "Akun Telegram ini belum terhubung ke SAHAJA AI.\n\n"
                . "Buka https://sahaja-ai.my.id/profile untuk menghubungkan.\n\n"
                . "Chat ID Anda: {$chatId}"
            );
            return response()->json(['ok' => true]);
        }

        // 5. Kirim indikator "typing", lalu proses AI
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
