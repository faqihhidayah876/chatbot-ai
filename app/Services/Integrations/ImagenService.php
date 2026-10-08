<?php

namespace App\Services\Integrations;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\Chat\ChatPersistenceService;

class ImagenService
{
    protected ChatPersistenceService $chatPersistence;

    public function __construct(ChatPersistenceService $chatPersistence)
    {
        $this->chatPersistence = $chatPersistence;
    }

    /**
     * Generate atau edit gambar.
     * Moved from ChatController@generateSahajaImagen
     */
    public function generate(
        string $prompt,
        int $sessionId,
        string $userMessage,
        ?array $imageArray = null
    ) {
        try {
            // 1. Bersihkan prompt
            $cleanPrompt = trim(str_ireplace('/imagen', '', $prompt));
            if (empty($cleanPrompt)) {
                $cleanPrompt = "A beautiful futuristic city landscape";
            }

            // ========================================================
            // ROBOT OTOMATIS
            // ========================================================
            $destinationPath = public_path('uploads/imagen');
            if (file_exists($destinationPath)) {
                $files = glob($destinationPath . '/*');
                $now   = time();
                foreach ($files as $file) {
                    if (is_file($file)) {
                        if ($now - filemtime($file) >= 60 * 60 * 24) {
                            unlink($file);
                        }
                    }
                }
            }

            // 2. LOGIKA ROUTING HYBRID
            if (!empty($imageArray) && count($imageArray) > 0) {

                // ========================================================
                // JALUR 1: MODE EDIT GAMBAR (Tetap Pakai FreeTheAI)
                // ========================================================
                $apiKey = config('services.freetheai.key');
                if (empty($apiKey)) throw new \Exception("API Key belum terpasang!");

                $baseUrl = rtrim(config('services.freetheai.base_url'), '/');
                $invokeUrl = $baseUrl . '/images/edits';
                $modelName = config('services.freetheai.model');

                $payload = [
                    'model' => $modelName,
                    'prompt' => $cleanPrompt,
                    'image' => $imageArray[0]
                ];

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ])
                ->withOptions(['verify' => config('services.ssl.ca_bundle')])
                ->timeout(120)
                ->post($invokeUrl, $payload);

                if (!$response->successful()) {
                    throw new \Exception("FreeTheAI Edit Server Error: " . $response->status());
                }

                $data = $response->json();
                $base64 = $data['data'][0]['b64_json'] ?? null;
                $imageUrl = $data['data'][0]['url'] ?? null;

                if ($base64) {
                    $imageName = 'imagen_edit_' . time() . '_' . rand(1000, 9999) . '.jpg';
                    if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);
                    file_put_contents($destinationPath . '/' . $imageName, base64_decode($base64));
                    $publicUrl = url('uploads/imagen/' . $imageName);
                    $markdownImage = "![Hasil Edit Imagen](" . $publicUrl . ")";
                } elseif ($imageUrl) {
                    $markdownImage = "![Hasil Edit Imagen](" . $imageUrl . ")";
                } else {
                    throw new \Exception("Gagal membaca struktur respons edit dari FreeTheAI.");
                }

                $aiReply = "**Sahaja Imagen** berhasil mengedit gambar anda!\n\n" . $markdownImage;
                $modelUsedLabel = 'Sahaja Imagen';

            } else {

                // ========================================================
                // JALUR 2: MODE GENERATE (CLOUDFLARE - FLUX 1 SCHNELL)
                // ========================================================
                $apiToken = config('services.cloudflare.api_token');
                $cfUrl = config('services.cloudflare.imagen_endpoint');

                if (empty($apiToken) || empty($cfUrl)) {
                    throw new \Exception("Konfigurasi Cloudflare (Token / Endpoint Imagen) di .env belum lengkap!");
                }

                // Tembak Server Cloudflare dengan format JSON standar (tanpa multipart)
                $response = Http::withToken($apiToken)
                    ->withOptions(['verify' => config('services.ssl.ca_bundle')])
                    ->timeout(120)
                    ->post($cfUrl, [
                        'prompt' => $cleanPrompt
                    ]);

                if (!$response->successful()) {
                    throw new \Exception("Server AI Error. Status: " . $response->status() . " | " . $response->body());
                }

                // DETEKTOR PINTAR: Cek tipe data balasan Cloudflare
                $contentType = $response->header('Content-Type');
                $imageContent = null;

                if (str_contains($contentType, 'application/json')) {
                    $data = $response->json();
                    if (isset($data['result']['image'])) {
                        $imageContent = base64_decode($data['result']['image']);
                    } else {
                        throw new \Exception("Server membalas dengan JSON yang bukan gambar: " . json_encode($data));
                    }
                } else {
                    $imageContent = $response->body();
                }

                $imageName = 'cf_flux_' . time() . '_' . rand(1000, 9999) . '.png';

                if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);

                // Simpan file ke server AlwaysData (Aman karena ada robot penyapu)
                file_put_contents($destinationPath . '/' . $imageName, $imageContent);
                $publicUrl = url('uploads/imagen/' . $imageName);

                // FIX BUG MARKDOWN
                $safeAltText = htmlspecialchars(substr(str_replace(["\r", "\n", "[", "]"], ' ', $cleanPrompt), 0, 40));

                $markdownImage = "![" . $safeAltText . "...](" . $publicUrl . ")";
                $aiReply = "**Sahaja Imagen** berhasil membuat gambar anda!\n\n" . $markdownImage;
                $modelUsedLabel = 'Sahaja Imagen (CF Flux)';
            }

            // ========================================================
            // PENYIMPANAN KE DATABASE CHAT
            // ========================================================
            $this->chatPersistence->saveChat(
                $sessionId,
                $userMessage,
                $aiReply,
                'imagen',
                str_contains($modelUsedLabel, 'CF') ? 'cloudflare' : 'freetheai',
                $modelUsedLabel
            );

            return response()->json([
                'session_id' => $sessionId,
                'user_message' => $userMessage,
                'ai_response' => $aiReply,
                'model_used' => $modelUsedLabel
            ]);

        } catch (\Exception $e) {
            Log::error('Sahaja Imagen Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => Auth::id(),
                'prompt'  => $cleanPrompt ?? null,
            ]);

            $errorMsg = "**Sahaja Imagen Mengalami Kendala Teknis**\n\n"
                      . "Layanan gambar sedang sibuk. Silakan coba lagi dalam beberapa saat.";
            return response()->json([
                'session_id' => $sessionId,
                'user_message' => $userMessage,
                'ai_response' => $errorMsg,
                'model_used' => 'Sahaja Imagen (Error)'
            ]);
        }
    }
}
