<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->decimal('sale_price_before_discount', 10, 2)->default(0);
            $table->decimal('sale_price_after_discount', 10, 2)->default(0);
            $table->unsignedInteger('quantity')->default(0);
            $table->longText('details')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
