<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->string('mode')->nullable()->after('ai_response');
            $table->string('provider')->nullable()->after('mode');
            $table->string('model')->nullable()->after('provider');
        });
    }

    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->dropColumn(['mode', 'provider', 'model']);
        });
    }
};
