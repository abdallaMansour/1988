<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('purchase_price_before_discount', 10, 2)->default(0);
            $table->decimal('purchase_price_after_discount', 10, 2)->default(0);
            $table->boolean('is_linked_to_novel')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('languages');
            $table->longText('details')->nullable();
            $table->boolean('is_related_to_another_issue')->default(false);
            $table->foreignId('related_issue_id')->nullable()->constrained('issues')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
