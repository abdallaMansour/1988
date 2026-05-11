<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issue_round_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_round_question_id')->constrained('issue_round_questions')->cascadeOnDelete();
            $table->text('answer');
            $table->unsignedTinyInteger('sort_order');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();

            $table->unique(['issue_round_question_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issue_round_answers');
    }
};
