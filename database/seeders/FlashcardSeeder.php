<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Helpers\CsvHelper;
use App\Models\Flashcard;
use App\Models\FlashcardEntry;
use DB;

class FlashcardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement('TRUNCATE flashcards');
        DB::statement('TRUNCATE flashcard_entries');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $flashcard = new Flashcard;
        $flashcard->name = "Lithuanian";
        $flashcard->primary_language = 353;
        $flashcard->secondary_language = 4;
        $flashcard->user_id = 1;
        $flashcard->save();

        $entries = CsvHelper::convertCsv('flashcard_lines.csv');
        foreach($entries as $entry) {
            $flashcardEntry = new FlashcardEntry;
            $flashcardEntry->question = $entry['lithuanian'];
            $flashcardEntry->answer = $entry['english'];
            $flashcardEntry->flashcard_id = $flashcard->id;
            $flashcardEntry->save();
        }
    }
}
