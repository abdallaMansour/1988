<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issue_forensic_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_id')->constrained('issues')->cascadeOnDelete();
            $table->string('title');
            $table->text('report');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issue_forensic_reports');
    }
};
