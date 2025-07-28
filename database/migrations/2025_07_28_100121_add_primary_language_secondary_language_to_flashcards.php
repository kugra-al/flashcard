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
        Schema::table('flashcards', function (Blueprint $table) {
            $table->unsignedBigInteger('primary_language')->nullable();
            $table->foreign('primary_language')->references('id')->on('languages');
            $table->unsignedBigInteger('secondary_language')->nullable();
            $table->foreign('secondary_language')->references('id')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flashcards', function (Blueprint $table) {
            $table->dropForeign(['primary_language', 'secondary_language']);
            $table->dropColumns(['primary_language', 'secondary_language']);
        });
    }
};
