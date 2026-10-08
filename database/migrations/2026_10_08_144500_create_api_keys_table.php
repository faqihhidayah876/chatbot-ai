<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Label custom, misal "VS Code Extension"
            $table->string('key_hash', 64)->unique(); // SHA-256 hash dari key asli
            $table->string('key_prefix', 16); // 8 char pertama untuk display: "sahaja_s"
            $table->string('last_four', 8); // 4 char terakhir untuk display
            $table->boolean('is_active')->default(true);
            $table->integer('daily_limit')->default(100); // Request per hari
            $table->integer('usage_today')->default(0);
            $table->integer('usage_total')->default(0);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('last_reset_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
            $table->index('key_hash');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_keys');
    }
};
