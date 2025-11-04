<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\QuizLevel;

class QuizLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (QuizLevel::count())
            return;
        $levels = [
            1 => 'Easy',
            2 => 'Medium',
            3 => 'Hard'
        ];
        foreach($levels as $id => $name) {
            $quizLevel = new QuizLevel;
            $quizLevel->name = $name;
            $quizLevel->save();
        }
    }
}
