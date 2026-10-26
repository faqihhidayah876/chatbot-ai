<?php

namespace App\Services\Agent;

use App\Models\AgentMemory;
use App\Models\User;
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

        $reply = $this->callMistral($messages);

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
            . "Jika user meminta hal di luar kemampuanmu saat ini (akses file/server), katakan apa adanya — jangan mengarang.\n"
            . "Versi ini belum punya tools; hanya percakapan.";
    }

    protected function callMistral(array $messages): string {
        $endpoint = (string) config('agent.mistral.endpoint');
        $key      = (string) config('agent.mistral.api_key');

        if ($key === '') {
            Log::error('AgentService: MISTRAL_API_KEY kosong');
            return 'Maaf, layanan AI belum dikonfigurasi (API key kosong).';
        }

        try {
            $resp = Http::timeout(45)->withHeaders([
                'Authorization' => "Bearer {$key}",
                'Content-Type'  => 'application/json',
            ])->post($endpoint, [
                'model'       => config('agent.mistral.model'),
                'messages'    => $messages,
                'max_tokens'  => config('agent.mistral.max_tokens'),
                'temperature' => config('agent.mistral.temperature'),
            ]);

            if (!$resp->ok()) {
                Log::error('Mistral error', [
                    'status' => $resp->status(),
                    'body'   => $resp->body(),
                ]);
                return 'Maaf, AI sedang gangguan. Coba lagi sebentar.';
            }

            $content = $resp->json('choices.0.message.content');
            return is_string($content) && trim($content) !== ''
                ? trim($content)
                : 'Maaf, saya tidak bisa merangkai jawaban saat ini.';
        } catch (\Throwable $e) {
            Log::error('Mistral exception', ['msg' => $e->getMessage()]);
            return 'Maaf, terjadi kesalahan saat menghubungi AI.';
        }
    }
}
