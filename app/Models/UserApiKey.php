<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;

class UserApiKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider',
        'base_url',
        'default_model',
        'label',
        'encrypted_key',
        'key_preview',
        'is_active',
        'is_valid',
        'last_validated_at',
        'last_used_at',
        'usage_count',
        'usage_today',
        'last_reset_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_valid' => 'boolean',
        'last_validated_at' => 'datetime',
        'last_used_at' => 'datetime',
        'last_reset_at' => 'datetime',
    ];

    protected $hidden = [
        'encrypted_key', // JANGAN pernah expose ke JSON
    ];

    /**
     * Provider yang didukung.
     */
    public const SUPPORTED_PROVIDERS = [
        // ===== TIER 1: PRESET (OpenAI-compatible populer) =====
        'openai' => [
            'name' => 'OpenAI',
            'tier' => 'preset',
            'test_endpoint' => 'https://api.openai.com/v1/models',
            'auth_type' => 'bearer',
            'auth_header' => 'Authorization',
            'auth_prefix' => 'Bearer ',
            'icon' => 'fa-robot',
            'placeholder' => 'sk-...',
        ],
        'anthropic' => [
            'name' => 'Anthropic (Claude)',
            'tier' => 'preset',
            'test_endpoint' => 'https://api.anthropic.com/v1/models',
            'auth_type' => 'header',
            'auth_header' => 'x-api-key',
            'auth_prefix' => '',
            'extra_headers' => [
                'anthropic-version' => '2023-06-01',
            ],
            'icon' => 'fa-feather',
            'placeholder' => 'sk-ant-...',
        ],
        'google' => [
            'name' => 'Google AI (Gemini)',
            'tier' => 'preset',
            'test_endpoint' => 'https://generativelanguage.googleapis.com/v1beta/models',
            'auth_type' => 'header',
            'auth_header' => 'x-goog-api-key',
            'auth_prefix' => '',
            'icon' => 'fa-google',
            'placeholder' => 'AIza...',
        ],
        'groq' => [
            'name' => 'Groq',
            'tier' => 'preset',
            'test_endpoint' => 'https://api.groq.com/openai/v1/models',
            'auth_type' => 'bearer',
            'auth_header' => 'Authorization',
            'auth_prefix' => 'Bearer ',
            'icon' => 'fa-bolt',
            'placeholder' => 'gsk_...',
        ],

        // ===== TIER 2: EXTENDED (OpenAI-compatible tambahan) =====
        'mistral' => [
            'name' => 'Mistral AI',
            'tier' => 'extended',
            'test_endpoint' => 'https://api.mistral.ai/v1/models',
            'auth_type' => 'bearer',
            'auth_header' => 'Authorization',
            'auth_prefix' => 'Bearer ',
            'icon' => 'fa-wind',
            'placeholder' => 'AIza... atau API key Mistral',
        ],
        'openrouter' => [
            'name' => 'OpenRouter',
            'tier' => 'extended',
            'test_endpoint' => 'https://openrouter.ai/api/v1/models',
            'auth_type' => 'bearer',
            'auth_header' => 'Authorization',
            'auth_prefix' => 'Bearer ',
            'extra_headers' => [
                'HTTP-Referer' => 'https://sahaja-ai.my.id',
                'X-Title' => 'SAHAJA AI',
            ],
            'icon' => 'fa-route',
            'placeholder' => 'sk-or-v1-...',
        ],
        'cerebras' => [
            'name' => 'Cerebras',
            'tier' => 'extended',
            'test_endpoint' => 'https://api.cerebras.ai/v1/models',
            'auth_type' => 'bearer',
            'auth_header' => 'Authorization',
            'auth_prefix' => 'Bearer ',
            'icon' => 'fa-microchip',
            'placeholder' => 'csk-...',
        ],
        'deepseek' => [
            'name' => 'DeepSeek',
            'tier' => 'extended',
            'test_endpoint' => 'https://api.deepseek.com/v1/models',
            'auth_type' => 'bearer',
            'auth_header' => 'Authorization',
            'auth_prefix' => 'Bearer ',
            'icon' => 'fa-water',
            'placeholder' => 'sk-...',
        ],

        // ===== TIER 3: CUSTOM (OpenAI-compatible endpoint apapun) =====
        'custom' => [
            'name' => 'Custom (OpenAI-compatible)',
            'tier' => 'custom',
            'test_endpoint' => null, // User input manual
            'auth_type' => 'bearer',
            'auth_header' => 'Authorization',
            'auth_prefix' => 'Bearer ',
            'icon' => 'fa-sliders',
            'placeholder' => 'sk-... atau key apapun',
        ],
    ];

    /**
     * Relasi ke user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Simpan key baru dengan enkripsi.
     */
    public static function store(
        int $userId, 
        string $provider, 
        string $plainKey, 
        ?string $label = null,
        ?string $baseUrl = null,
        ?string $defaultModel = null
    ): self {
        $keyPreview = self::generatePreview($plainKey);

        return self::create([
            'user_id' => $userId,
            'provider' => $provider,
            'base_url' => $baseUrl,
            'default_model' => $defaultModel,
            'label' => $label,
            'encrypted_key' => Crypt::encryptString($plainKey),
            'key_preview' => $keyPreview,
            'is_active' => true,
            'is_valid' => true,
            'last_validated_at' => now(),
            'last_reset_at' => now(),
        ]);
    }

    /**
     * Get decrypted plain key.
     */
    public function getPlainKey(): ?string
    {
        try {
            return Crypt::decryptString($this->encrypted_key);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Preview key: "sk-abc...xyz1"
     */
    private static function generatePreview(string $plainKey): string
    {
        $len = strlen($plainKey);
        if ($len <= 12) {
            return substr($plainKey, 0, 4) . '...';
        }
        return substr($plainKey, 0, 8) . '...' . substr($plainKey, -4);
    }

    /**
     * Cek kuota harian (untuk BYOK kita tidak limit ketat, hanya tracking).
     */
    public function resetDailyIfNeeded(): void
    {
        $lastReset = $this->last_reset_at ? $this->last_reset_at->toDateString() : null;
        $today = now()->toDateString();

        if ($lastReset !== $today) {
            $this->usage_today = 0;
            $this->last_reset_at = now();
            $this->save();
        }
    }

    /**
     * Increment usage counter.
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
        $this->increment('usage_today');
        $this->last_used_at = now();
        $this->save();
    }

    /**
     * Mark sebagai invalid (kalau key ditolak provider).
     */
    public function markInvalid(): void
    {
        $this->is_valid = false;
        $this->save();
    }

    /**
     * Mark sebagai valid setelah test sukses.
     */
    public function markValid(): void
    {
        $this->is_valid = true;
        $this->last_validated_at = now();
        $this->save();
    }
}
