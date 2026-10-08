<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_key_id')->constrained()->onDelete('cascade');
            $table->string('endpoint', 100); // /api/v1/chat
            $table->string('method', 10); // POST
            $table->string('mode', 30)->nullable(); // fast, smart, coding, dll
            $table->integer('input_length')->default(0); // Panjang input
            $table->integer('output_length')->default(0); // Panjang output
            $table->integer('status_code')->default(200);
            $table->integer('response_time_ms')->default(0); // Berapa lama process
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index('api_key_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_usage_logs');
    }
};
