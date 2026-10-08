<?php

namespace App\Services\Chat;

use App\Models\Chat;
use App\Models\Session;
use Illuminate\Support\Str;

class ChatPersistenceService
{
    /**
     * Cari atau buat session baru.
     */
    public function findOrCreateSession(int $userId, ?int $sessionId, string $userMessage): int
    {
        if ($sessionId) {
            $session = Session::where('id', $sessionId)->where('user_id', $userId)->first();
            if ($session) {
                $session->touch();
                return $session->id;
            }
        }

        // Cek duplicate (dalam 15 detik terakhir)
        $title = Str::words($userMessage, 5, '...');
        $recentSession = Session::where('user_id', $userId)
            ->where('title', $title)
            ->where('created_at', '>=', now()->subSeconds(15))
            ->first();

        if ($recentSession) {
            return $recentSession->id;
        }

        $session = Session::create([
            'user_id' => $userId,
            'title' => $title,
        ]);

        return $session->id;
    }

    /**
     * Simpan chat normal.
     */
    public function saveChat(
        int $sessionId,
        string $userMessage,
        string $aiResponse,
        ?string $mode = null,
        ?string $provider = null,
        ?string $model = null
    ): Chat {
        return Chat::create([
            'session_id' => $sessionId,
            'user_message' => $userMessage,
            'ai_response' => $aiResponse,
            'mode' => $mode,
            'provider' => $provider,
            'model' => $model,
        ]);
    }
}
