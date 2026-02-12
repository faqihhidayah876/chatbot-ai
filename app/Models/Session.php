<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'chat_sessions';

    protected $fillable = [
        'user_id', // TAMBAHKAN INI
        'title'
    ];

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    // Relasi ke User (Opsional tapi bagus)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
