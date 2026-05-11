<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LlmDocument extends Model
{
    use HasFactory;

    protected $fillable = ['workspace_id', 'file_name', 'content'];

    // Relasi balik ke Workspace
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
