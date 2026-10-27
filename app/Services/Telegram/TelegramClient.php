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
        try {
            $resp = $this->http(15)->post("{$this->base}/sendMessage", [
                'chat_id'                  => $chatId,
                'text'                     => $text,
                'parse_mode'               => 'Markdown',
                'disable_web_page_preview' => true,
            ]);
            if (!$resp->ok() || $resp->json('ok') !== true) {
                // Jika error karena markdown tidak valid, coba kirim sebagai teks biasa
                $retry = $this->http(15)->post("{$this->base}/sendMessage", [
                    'chat_id'                  => $chatId,
                    'text'                     => strip_tags($text),
                    'disable_web_page_preview' => true,
                ]);
                return $retry->ok() && $retry->json('ok') === true;
            }
            return true;
        } catch (\Throwable $e) {
            Log::error('Telegram sendMessage exception', ['msg' => $e->getMessage()]);
            return false;
        }
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
}
