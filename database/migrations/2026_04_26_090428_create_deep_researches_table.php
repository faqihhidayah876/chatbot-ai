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
        Schema::create('deep_researches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('session_id')->nullable(); // Terhubung ke room chat yang mana
            $table->text('topic'); // Topik yang diteliti
            $table->string('status')->default('inisialisasi'); // inisialisasi, mencari_data, menganalisis, selesai, error
            $table->json('logs')->nullable(); // Menyimpan rekaman jejak alur berpikir AI
            $table->longText('result_markdown')->nullable(); // Hasil akhir penelitian
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deep_researches');
    }
};
