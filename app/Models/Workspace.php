<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description'];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Dokumen (Satu workspace punya banyak dokumen)
    public function documents()
    {
        return $this->hasMany(LlmDocument::class);
    }
}
