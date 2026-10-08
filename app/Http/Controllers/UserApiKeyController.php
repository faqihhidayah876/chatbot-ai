<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\UserApiKey;
use App\Services\Ai\ByokService;

class UserApiKeyController extends Controller
{
    protected ByokService $byokService;

    public function __construct(ByokService $byokService)
    {
        $this->byokService = $byokService;
    }

    /**
     * List semua user API keys.
     */
    public function index()
    {
        $keys = UserApiKey::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($key) {
                return [
                    'id' => $key->id,
                    'provider' => $key->provider,
                    'label' => $key->label,
                    'key_preview' => $key->key_preview,
                    'is_active' => $key->is_active,
                    'is_valid' => $key->is_valid,
                    'usage_count' => $key->usage_count,
                    'usage_today' => $key->usage_today,
                    'last_used_at' => $key->last_used_at?->diffForHumans(),
                    'last_validated_at' => $key->last_validated_at?->diffForHumans(),
                ];
            });

        return response()->json([
            'success' => true,
            'keys' => $keys,
            'supported_providers' => collect(UserApiKey::SUPPORTED_PROVIDERS)
                ->map(fn($p, $k) => [
                    'id' => $k,
                    'name' => $p['name'],
                    'tier' => $p['tier'] ?? 'preset',
                    'icon' => $p['icon'] ?? 'fa-key',
                    'placeholder' => $p['placeholder'] ?? '',
                ])
                ->values(),
        ]);
    }

    /**
     * Store API key baru.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'provider' => 'required|string|in:' . implode(',', array_keys(UserApiKey::SUPPORTED_PROVIDERS)),
                'api_key' => 'required|string|min:10|max:500',
                'label' => 'nullable|string|max:100',
                'base_url' => 'nullable|required_if:provider,custom|url|max:255',
                'default_model' => 'nullable|string|max:100',
            ]);

            $userId = Auth::id();
            $provider = $request->input('provider');
            $plainKey = trim($request->input('api_key'));
            $label = $request->input('label');
            $baseUrl = $request->input('base_url');
            $defaultModel = $request->input('default_model');

            // Cek limit: max 5 key total per user (bukan per provider, 
            // supaya user bisa coba banyak provider)
            $existingCount = UserApiKey::where('user_id', $userId)->count();

            if ($existingCount >= 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maksimal 5 API key. Hapus salah satu dulu.',
                ], 400);
            }

            // Validasi key
            $validation = $this->byokService->validateKey($provider, $plainKey, $baseUrl);

            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $validation['message'],
                ], 400);
            }

            // Simpan
            $key = UserApiKey::store($userId, $provider, $plainKey, $label, $baseUrl, $defaultModel);

            Log::info('User API key stored', [
                'user_id' => $userId,
                'provider' => $provider,
                'key_id' => $key->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'API key berhasil disimpan.',
                'key' => [
                    'id' => $key->id,
                    'provider' => $key->provider,
                    'label' => $key->label,
                    'key_preview' => $key->key_preview,
                ],
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Store user API key error', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan API key. Coba lagi.',
            ], 500);
        }
    }

    /**
     * Test API key yang sudah tersimpan (re-validate).
     */
    public function test($id)
    {
        try {
            $key = UserApiKey::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $plainKey = $key->getPlainKey();

            if (!$plainKey) {
                $key->markInvalid();
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membaca key terenkripsi.',
                ], 500);
            }

            $validation = $this->byokService->validateKey($key->provider, $plainKey, $key->base_url);

            if ($validation['valid']) {
                $key->markValid();
            } else {
                $key->markInvalid();
            }

            return response()->json([
                'success' => true,
                'is_valid' => $validation['valid'],
                'message' => $validation['message'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal test API key.',
            ], 500);
        }
    }

    /**
     * Toggle active status.
     */
    public function toggle($id)
    {
        try {
            $key = UserApiKey::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $key->is_active = !$key->is_active;
            $key->save();

            return response()->json([
                'success' => true,
                'is_active' => $key->is_active,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status.',
            ], 500);
        }
    }

    /**
     * Hapus API key (permanent).
     */
    public function destroy($id)
    {
        try {
            $key = UserApiKey::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            Log::info('User API key deleted', [
                'user_id' => Auth::id(),
                'key_id' => $key->id,
                'provider' => $key->provider,
            ]);

            // Force delete (bukan soft delete) — supaya key 
            // benar-benar hilang dari DB
            $key->forceDelete();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus API key.',
            ], 500);
        }
    }
}
