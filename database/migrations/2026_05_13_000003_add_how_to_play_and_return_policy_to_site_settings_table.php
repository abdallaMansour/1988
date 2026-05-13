<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->longText('how_to_play')->nullable()->after('about_us');
            $table->longText('return_replacement_policy')->nullable()->after('how_to_play');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['how_to_play', 'return_replacement_policy']);
        });
    }
};
