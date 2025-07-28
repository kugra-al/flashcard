<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Flashcard;

class FlashcardEntry extends Model
{
    public function flashcard() {
        return $this->belongsTo(Flashcard::class, 'flashcard_id');
    }
}
