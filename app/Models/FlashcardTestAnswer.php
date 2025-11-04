<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashcardTestAnswer extends Model
{
    protected $fillable = [
        'user_id',
        'flashcard_id',
        'question_text',
        'answer_text',
        'correct',
    ];
}
