<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('investigator_name', 255)->nullable();
        });

        foreach (DB::table('users')->orderBy('id')->cursor() as $user) {
            DB::table('users')->where('id', $user->id)->update([
                'investigator_name' => 'player_'.$user->id,
            ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->unique('investigator_name');
        });

        DB::table('users')->whereNull('email_verified_at')->update(['email_verified_at' => now()]);

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn(['phone', 'phone_verified_at']);
            }
        });

        Schema::dropIfExists('verification_codes');
    }

    public function down(): void
    {
        Schema::create('verification_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20);
            $table->string('target');
            $table->string('code', 6);
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index(['user_id', 'type']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['investigator_name']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('investigator_name');
            $table->string('phone', 20)->nullable()->after('email');
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
        });
    }
};
