<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->uuid('checkout_batch_id')->nullable()->after('user_id')->index();
            $table->unsignedInteger('quantity')->default(1)->after('purchasable_id');
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn(['checkout_batch_id', 'quantity']);
        });
    }
};
