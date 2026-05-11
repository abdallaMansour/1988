<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issue_round_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_round_id')->constrained('issue_rounds')->cascadeOnDelete();
            $table->text('question');
            $table->unsignedTinyInteger('sort_order');
            $table->timestamps();

            $table->unique(['issue_round_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issue_round_questions');
    }
};
