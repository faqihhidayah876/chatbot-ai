<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title')->nullable(); // Judul chat di sidebar
            $table->timestamps();
        });

        // Kita update tabel chats agar punya relasi ke sessions
        Schema::table('chats', function (Blueprint $table) {
            $table->foreignId('session_id')
              ->nullable()
              ->constrained('chat_sessions') // Arahkan ke tabel yang baru diganti namanya
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
            $table->dropColumn('session_id');
        });
        Schema::dropIfExists('chat_sessions');
    }
};
