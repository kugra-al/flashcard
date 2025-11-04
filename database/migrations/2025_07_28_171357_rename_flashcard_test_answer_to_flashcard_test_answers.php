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
        Schema::table('flashcard_test_answer', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['flashcard_id']);
        });
        Schema::rename('flashcard_test_answer', 'flashcard_test_answers');
        Schema::table('flashcard_test_answers', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('flashcard_id')->references('id')->on('flashcards');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flashcard_test_answers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['flashcard_id']);
        });
        Schema::rename('flashcard_test_answers', 'flashcard_test_answer');
        Schema::table('flashcard_test_answer', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('flashcard_id')->references('id')->on('flashcards');
        });
    }
};
