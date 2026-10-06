<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'session_id',
        'user_message',
        'ai_response',
        'mode',
        'provider',
        'model',
    ];
    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}
