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

    public function sendMessage(int|string $chatId, string $text): bool {
        try {
            $resp = Http::timeout(15)->post("{$this->base}/sendMessage", [
                'chat_id'                  => $chatId,
                'text'                     => $text,
                'disable_web_page_preview' => true,
            ]);
            if (!$resp->ok() || $resp->json('ok') !== true) {
                Log::warning('Telegram sendMessage failed', ['body' => $resp->body()]);
                return false;
            }
            return true;
        } catch (\Throwable $e) {
            Log::error('Telegram sendMessage exception', ['msg' => $e->getMessage()]);
            return false;
        }
    }

    public function sendChatAction(int|string $chatId, string $action = 'typing'): void {
        try {
            Http::timeout(5)->post("{$this->base}/sendChatAction", [
                'chat_id' => $chatId,
                'action'  => $action,
            ]);
        } catch (\Throwable $e) {
            // diamkan — chat action bersifat opsional
        }
    }

    public function setWebhook(string $url, string $secret): bool {
        try {
            $resp = Http::timeout(20)->post("{$this->base}/setWebhook", [
                'url'             => $url,
                'secret_token'    => $secret,
                'allowed_updates' => ['message'],
            ]);
            return $resp->ok() && $resp->json('ok') === true;
        } catch (\Throwable $e) {
            Log::error('Telegram setWebhook exception', ['msg' => $e->getMessage()]);
            return false;
        }
    }

    public function deleteWebhook(): bool {
        try {
            $resp = Http::timeout(20)->post("{$this->base}/deleteWebhook");
            return $resp->ok() && $resp->json('ok') === true;
        } catch (\Throwable $e) {
            Log::error('Telegram deleteWebhook exception', ['msg' => $e->getMessage()]);
            return false;
        }
    }

    public function getWebhookInfo(): array {
        try {
            $resp = Http::timeout(20)->get("{$this->base}/getWebhookInfo");
            return $resp->json() ?? [];
        } catch (\Throwable $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
