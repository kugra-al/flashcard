<?php

namespace App\Services;

use App\Models\User;
use App\Models\Flashcard;

class FlashcardTest
{
    protected $questions;

    public function startSession(User $user, Flashcard $flashcard, $questions)
    {
        $allEntries = $flashcard->entries()->inRandomOrder()->get();

        $this->questions = $allEntries->random(min($questions, $allEntries->count()));

        $quiz = $this->questions->map(function ($entry) use ($allEntries) {
            return $this->prepareQuestion($entry, $allEntries);
        });

        return $quiz->values();
    }

    protected function prepareQuestion($entry, $allEntries)
    {
        $correctAnswer = $entry->answer;

        $incorrectAnswers = $allEntries
            ->where('id', '!=', $entry->id)
            ->pluck('answer')
            ->unique()
            ->shuffle()
            ->take(4)
            ->values();

        $allAnswers = $incorrectAnswers->push($correctAnswer)->shuffle()->values();

        return [
            'question' => $entry->question,
            'answers' => $allAnswers,
            'correctAnswer' => $correctAnswer,
            'entryId' => $entry->id,
        ];
    }
}
