<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_api_keys', function (Blueprint $table) {
            $table->string('base_url')->nullable()->after('provider');
            $table->string('default_model')->nullable()->after('base_url');
        });
    }

    public function down(): void
    {
        Schema::table('user_api_keys', function (Blueprint $table) {
            $table->dropColumn(['base_url', 'default_model']);
        });
    }
};
