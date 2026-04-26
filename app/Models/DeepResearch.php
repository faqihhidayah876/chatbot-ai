<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeepResearch extends Model
{
    use HasFactory;

    protected $table = 'deep_researches';

    protected $fillable = [
        'user_id',
        'session_id',
        'topic',
        'status',
        'logs',
        'result_markdown'
    ];

    // JURUS RAHASIA: Otomatis ubah JSON di database menjadi Array di PHP
    protected $casts = [
        'logs' => 'array',
    ];
}
