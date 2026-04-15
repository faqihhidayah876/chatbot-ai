<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\FaseLike;
use App\Models\FaseComment;

class FasePost extends Model
{
    use HasFactory;

    protected $guarded = []; // Izinkan insert semua kolom

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function likes()
    {
        return $this->hasMany(FaseLike::class);
    }
    public function comments()
    {
        return $this->hasMany(FaseComment::class);
    }
}
