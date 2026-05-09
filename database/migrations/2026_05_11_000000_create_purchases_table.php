<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('purchasable');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10)->default('AED');
            $table->string('status')->default('pending')->index();
            $table->string('ziina_payment_intent_id')->nullable()->index();
            $table->string('ziina_operation_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
