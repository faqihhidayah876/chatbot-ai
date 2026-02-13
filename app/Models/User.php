<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    // Relasi Chat (Lewat Session - Optional, tapi untuk statistik sederhana kita hitung manual di view/controller bisa)
    // Cara mudah hitung chat user:
    public function getChatCountAttribute()
    {
        // Menghitung total chat dari semua sesi milik user ini
        return $this->sessions()->withCount('chats')->get()->sum('chats_count');
    }
}
