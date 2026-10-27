<?php

namespace App\Services\Telegram;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramClient {
    protected string $base;

    public function __construct() {
        $token = (string) config('telegram.bot_token');
        $this->base = rtrim((string) config('telegram.api_base'), '/') . $token;
    }

    protected function http(int $timeout) {
        return Http::withOptions([
            'verify' => config('services.ssl.ca_bundle', true),
        ])->timeout($timeout);
    }

    public function sendMessage(int|string $chatId, string $text): bool {
        // 1. Telegram max 4096 chars. Pecah teks jadi beberapa bagian.
        $chunks = str_split($text, 4000);
        $success = true;

        foreach ($chunks as $chunk) {
            try {
                $resp = $this->http(15)->post("{$this->base}/sendMessage", [
                    'chat_id'                  => $chatId,
                    'text'                     => $chunk,
                    'parse_mode'               => 'Markdown',
                    'disable_web_page_preview' => true,
                ]);
                // Jika Markdown gagal (misal tabel bikin error), kirim sebagai teks biasa
                if (!$resp->ok() || $resp->json('ok') !== true) {
                    $this->http(15)->post("{$this->base}/sendMessage", [
                        'chat_id'                  => $chatId,
                        'text'                     => strip_tags($chunk),
                        'disable_web_page_preview' => true,
                    ]);
                }
            } catch (\Throwable $e) {
                $success = false;
            }
            // Jeda 500ms agar tidak kena rate limit Telegram
            usleep(500000);
        }
        return $success;
    }

    public function sendChatAction(int|string $chatId, string $action = 'typing'): void {
        try {
            $this->http(5)->post("{$this->base}/sendChatAction", [
                'chat_id' => $chatId,
                'action'  => $action,
            ]);
        } catch (\Throwable $e) {
            // diamkan — chat action bersifat opsional
        }
    }

    public function setWebhook(string $url, string $secret): bool {
        try {
            $resp = $this->http(20)->post("{$this->base}/setWebhook", [
                'url'             => $url,
                'secret_token'    => $secret,
                'allowed_updates' => ['message', 'callback_query'],
            ]);
            return $resp->ok() && $resp->json('ok') === true;
        } catch (\Throwable $e) {
            Log::error('Telegram setWebhook exception', ['msg' => $e->getMessage()]);
            return false;
        }
    }

    public function deleteWebhook(): bool {
        try {
            $resp = $this->http(20)->post("{$this->base}/deleteWebhook");
            return $resp->ok() && $resp->json('ok') === true;
        } catch (\Throwable $e) {
            Log::error('Telegram deleteWebhook exception', ['msg' => $e->getMessage()]);
            return false;
        }
    }

    public function getWebhookInfo(): array {
        try {
            $resp = $this->http(20)->get("{$this->base}/getWebhookInfo");
            return $resp->json() ?? [];
        } catch (\Throwable $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function sendMessageWithInlineKeyboard(int|string $chatId, string $text, array $buttons): bool {
        try {
            $resp = $this->http(15)->post("{$this->base}/sendMessage", [
                'chat_id'                  => $chatId,
                'text'                     => $text,
                'parse_mode'               => 'Markdown',
                'disable_web_page_preview' => true,
                'reply_markup'             => json_encode(['inline_keyboard' => $buttons]),
            ]);
            return $resp->ok() && $resp->json('ok') === true;
        } catch (\Throwable $e) {
            Log::error('Telegram inline keyboard exception', ['msg' => $e->getMessage()]);
            return false;
        }
    }

    public function answerCallbackQuery(string $callbackQueryId, string $text = ''): bool {
        try {
            $resp = $this->http(5)->post("{$this->base}/answerCallbackQuery", [
                'callback_query_id' => $callbackQueryId,
                'text'              => $text,
            ]);
            return $resp->ok() && $resp->json('ok') === true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function setMyCommands(array $commands): bool {
        try {
            $resp = $this->http(20)->post("{$this->base}/setMyCommands", [
                'commands' => $commands,
            ]);
            return $resp->ok() && $resp->json('ok') === true;
        } catch (\Throwable $e) {
            Log::error('Telegram setMyCommands exception', ['msg' => $e->getMessage()]);
            return false;
        }
    }

    public function getFileUrl(string $fileId): ?string {
        try {
            // 1. Minta path file ke Telegram
            $resp = $this->http(10)->post("{$this->base}/getFile", ['file_id' => $fileId]);
            if (!$resp->ok() || $resp->json('ok') !== true) return null;
            
            $filePath = $resp->json('result.file_path');
            
            // 2. Kembalikan URL download lengkap
            $token = config('telegram.bot_token');
            return "https://api.telegram.org/file/bot{$token}/{$filePath}";
        } catch (\Throwable $e) {
            Log::error('Telegram getFile error', ['msg' => $e->getMessage()]);
            return null;
        }
    }

    public function downloadFile(string $fileUrl): ?string {
        try {
            $resp = $this->http(60)->get($fileUrl);
            if ($resp->ok()) {
                return $resp->body();
            }
            return null;
        } catch (\Throwable $e) {
            Log::error('Telegram downloadFile error', ['msg' => $e->getMessage()]);
            return null;
        }
    }
}
