<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FaseComment extends Model
{
    use HasFactory;

    protected $guarded = []; // Izinkan insert semua kolom

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // (Opsional) Relasi ke Postingan jika suatu saat butuh
    public function post()
    {
        return $this->belongsTo(FasePost::class, 'fase_post_id');
    }
}
