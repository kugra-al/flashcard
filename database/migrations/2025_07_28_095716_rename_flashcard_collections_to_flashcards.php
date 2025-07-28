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
        Schema::table('flashcard_collections', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::rename('flashcard_collections', 'flashcards');
        Schema::table('flashcards', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flashcards', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::rename('flashcards', 'flashcard_collections');
        Schema::table('flashcard_collections', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
};
