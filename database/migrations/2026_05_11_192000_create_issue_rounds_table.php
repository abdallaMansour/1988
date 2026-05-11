<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issue_rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_id')->constrained('issues')->cascadeOnDelete();
            $table->unsignedTinyInteger('round_number');
            $table->timestamps();

            $table->unique(['issue_id', 'round_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issue_rounds');
    }
};
