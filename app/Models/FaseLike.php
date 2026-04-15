<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FaseLike extends Model
{
    use HasFactory;

    protected $guarded = []; // Izinkan insert semua kolom
}
