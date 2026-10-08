<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_api_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('provider', 32); // openai, anthropic, google, groq
            $table->string('label')->nullable(); // Custom label, misal "Akun Kerja"
            $table->text('encrypted_key'); // Encrypted dengan Crypt::encryptString
            $table->string('key_preview', 32); // Untuk display: "sk-...abc1"
            $table->boolean('is_active')->default(true);
            $table->boolean('is_valid')->default(true);
            $table->timestamp('last_validated_at')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->integer('usage_count')->default(0);
            $table->integer('usage_today')->default(0);
            $table->timestamp('last_reset_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'provider']);
            $table->unique(['user_id', 'provider', 'label']); // Cegah duplicate label
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_api_keys');
    }
};
