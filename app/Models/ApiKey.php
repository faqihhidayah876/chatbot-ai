<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'key_hash',
        'key_prefix',
        'last_four',
        'is_active',
        'daily_limit',
        'usage_today',
        'usage_total',
        'last_used_at',
        'last_reset_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
        'last_reset_at' => 'datetime',
    ];

    protected $hidden = [
        'key_hash',
    ];

    /**
     * Relasi ke user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke usage logs.
     */
    public function usageLogs()
    {
        return $this->hasMany(ApiUsageLog::class);
    }

    /**
     * Generate API key baru.
     * Return tuple: [plainKey, ApiKey instance]
     */
    public static function generate(int $userId, string $name = 'Untitled Key', int $dailyLimit = 100): array
    {
        // Format: sahaja_sk_<32 random chars>
        $random = Str::random(32);
        $plainKey = 'sahaja_sk_' . $random;

        // Hash untuk disimpan
        $keyHash = hash('sha256', $plainKey);

        // Untuk display: "sahaja_s...abc123"
        $keyPrefix = substr($plainKey, 0, 12); // "sahaja_sk_XX"
        $lastFour = substr($plainKey, -4);

        $apiKey = self::create([
            'user_id' => $userId,
            'name' => $name,
            'key_hash' => $keyHash,
            'key_prefix' => $keyPrefix,
            'last_four' => $lastFour,
            'is_active' => true,
            'daily_limit' => $dailyLimit,
            'usage_today' => 0,
            'usage_total' => 0,
            'last_reset_at' => now(),
        ]);

        return [$plainKey, $apiKey];
    }

    /**
     * Cari ApiKey dari plain text key.
     */
    public static function findByPlainKey(string $plainKey): ?self
    {
        $hash = hash('sha256', $plainKey);
        return self::where('key_hash', $hash)->first();
    }

    /**
     * Cek apakah usage masih dalam limit harian.
     */
    public function hasQuotaRemaining(): bool
    {
        // Reset otomatis kalau ganti hari
        $this->resetDailyIfNeeded();

        return $this->usage_today < $this->daily_limit;
    }

    /**
     * Reset usage harian kalau sudah ganti hari.
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
        $this->increment('usage_today');
        $this->increment('usage_total');
        $this->last_used_at = now();
        $this->save();
    }

    /**
     * Get masked key untuk display di UI.
     */
    public function getMaskedKeyAttribute(): string
    {
        return $this->key_prefix . '...' . $this->last_four;
    }
}
