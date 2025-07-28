<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language;
use App\Models\User;
use App\Models\FlashcardEntry;

class Flashcard extends Model
{
    protected $fillable = ['name', 'primary_language', 'secondary_language', 'user_id'];


    public function primary_language()
    {
        return $this->belongsTo(Language::class, 'primary_language');
    }

    public function secondary_language()
    {
        return $this->belongsTo(Language::class, 'secondary_language');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function entries() {
        return $this->hasMany(FlashcardEntry::class, 'flashcard_id');
    }
}
