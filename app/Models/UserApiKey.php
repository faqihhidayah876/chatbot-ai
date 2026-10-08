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
        'openai' => [
            'name' => 'OpenAI',
            'test_endpoint' => 'https://api.openai.com/v1/models',
            'test_method' => 'GET',
        ],
        'anthropic' => [
            'name' => 'Anthropic (Claude)',
            'test_endpoint' => 'https://api.anthropic.com/v1/models',
            'test_method' => 'GET',
        ],
        'google' => [
            'name' => 'Google AI (Gemini)',
            'test_endpoint' => 'https://generativelanguage.googleapis.com/v1beta/models',
            'test_method' => 'GET',
        ],
        'groq' => [
            'name' => 'Groq',
            'test_endpoint' => 'https://api.groq.com/openai/v1/models',
            'test_method' => 'GET',
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
    public static function store(int $userId, string $provider, string $plainKey, ?string $label = null): self
    {
        $keyPreview = self::generatePreview($plainKey);

        return self::create([
            'user_id' => $userId,
            'provider' => $provider,
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
