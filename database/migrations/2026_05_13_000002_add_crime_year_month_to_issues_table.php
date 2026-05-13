<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->unsignedSmallInteger('crime_year')->nullable()->after('crime_type');
            $table->unsignedTinyInteger('crime_month')->nullable()->after('crime_year');
        });
    }

    public function down(): void
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->dropColumn(['crime_year', 'crime_month']);
        });
    }
};
