<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_result_id')->constrained(table: 'questionnaire_results')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('question_id')->constrained(table: 'questions')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('scale_value')->nullable();
            $table->string('text_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
