<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('discount_type');
            $table->decimal('discount_value', 12, 2);
            $table->unsignedInteger('total_usage_limit')->nullable();
            $table->unsignedInteger('per_user_usage_limit')->nullable();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->string('applies_to');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('coupon_product', function (Blueprint $table) {
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->primary(['coupon_id', 'product_id']);
        });

        Schema::create('coupon_issue', function (Blueprint $table) {
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('issue_id')->constrained()->cascadeOnDelete();
            $table->primary(['coupon_id', 'issue_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_issue');
        Schema::dropIfExists('coupon_product');
        Schema::dropIfExists('coupons');
    }
};
