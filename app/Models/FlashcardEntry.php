<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Flashcard;

class FlashcardEntry extends Model
{
    protected $fillable = ['question', 'answer', 'hint', 'flashcard_id'];
    public function flashcard() {
        return $this->belongsTo(Flashcard::class, 'flashcard_id');
    }
}
