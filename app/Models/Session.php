<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'chat_sessions';

    protected $fillable = ['title'];

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
}
