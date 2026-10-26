<?php

namespace App\Console\Commands;

use App\Services\Telegram\TelegramClient;
use Illuminate\Console\Command;

class TelegramSetWebhookCommand extends Command {
    protected $signature = 'telegram:set-webhook
                            {--url= : Override URL webhook}
                            {--delete : Hapus webhook}
                            {--info : Tampilkan info webhook saat ini}';

    protected $description = 'Set / hapus / info webhook bot Telegram SAHAJA Agent';

    public function handle(TelegramClient $tg): int {
        if ($this->option('delete')) {
            $ok = $tg->deleteWebhook();
            $this->info($ok ? 'Webhook dihapus.' : 'Gagal hapus webhook.');
            return $ok ? self::SUCCESS : self::FAILURE;
        }

        if ($this->option('info')) {
            $info = $tg->getWebhookInfo();
            $this->line(json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            return self::SUCCESS;
        }

        $url    = $this->option('url') ?: (rtrim((string) config('app.url'), '/') . '/api/telegram/webhook');
        $secret = (string) config('telegram.webhook_secret');

        if ($secret === '') {
            $this->error('TELEGRAM_WEBHOOK_SECRET belum diset di .env');
            return self::FAILURE;
        }

        $ok = $tg->setWebhook($url, $secret);
        $this->info($ok ? "Webhook diset ke: {$url}" : 'Gagal set webhook (lihat log).');
        return $ok ? self::SUCCESS : self::FAILURE;
    }
}
