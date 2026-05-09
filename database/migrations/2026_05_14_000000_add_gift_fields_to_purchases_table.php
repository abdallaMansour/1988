<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('gift_claim_token', 64)->nullable()->unique()->after('ziina_operation_id');
            $table->string('gift_invite_email')->nullable()->after('gift_claim_token');
            $table->foreignId('gift_from_user_id')->nullable()->after('gift_invite_email')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['gift_from_user_id']);
            $table->dropColumn(['gift_claim_token', 'gift_invite_email', 'gift_from_user_id']);
        });
    }
};
