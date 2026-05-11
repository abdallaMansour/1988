<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->unique();
            $table->string('english_name');
            $table->timestamps();
        });

        DB::table('languages')->insert([
            ['name' => 'العربية', 'code' => 'ar', 'english_name' => 'Arabic', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'الإنجليزية', 'code' => 'en', 'english_name' => 'English', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'الفرنسية', 'code' => 'fr', 'english_name' => 'French', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'الألمانية', 'code' => 'de', 'english_name' => 'German', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'الإسبانية', 'code' => 'es', 'english_name' => 'Spanish', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'الإيطالية', 'code' => 'it', 'english_name' => 'Italian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'التركية', 'code' => 'tr', 'english_name' => 'Turkish', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'الأردية', 'code' => 'ur', 'english_name' => 'Urdu', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
