<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_avatars', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('profile_avatar_id')
                ->nullable()
                ->after('investigator_name')
                ->constrained('profile_avatars')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('profile_avatar_id');
        });

        Schema::dropIfExists('profile_avatars');
    }
};
