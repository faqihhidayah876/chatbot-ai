<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApiUsageLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_key_id',
        'endpoint',
        'method',
        'mode',
        'input_length',
        'output_length',
        'status_code',
        'response_time_ms',
        'ip_address',
    ];

    public function apiKey()
    {
        return $this->belongsTo(ApiKey::class);
    }
}
