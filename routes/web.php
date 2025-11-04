<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\FlashcardController;
use App\Models\Country;
use App\Models\Language;
use App\Models\Flashcard;
use App\Models\QuizLevel;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))
        ->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Flashcards
    |--------------------------------------------------------------------------
    */
    Route::get('/flashcards', function () {
        $flashcards = Flashcard::with(['primary_language', 'secondary_language', 'user', 'entries'])
            ->get();

        return Inertia::render('Flashcard/Index', ['flashcards' => $flashcards]);
    })->name('flashcards');

    Route::get('/flashcard/{id}', function ($id) {
        $flashcard = Flashcard::with([
            'primary_language',
            'secondary_language',
            'user',
            'entries' => fn($q) => $q->orderBy('question', 'asc')
        ])->findOrFail($id);

        return Inertia::render('Flashcard/Show', ['flashcard' => $flashcard]);
    })->name('flashcard.show');

    Route::post('/api/submit-flashcard', [FlashcardController::class, 'store'])
        ->name('flashcard.store');

    Route::post('/flashcard/{id}/addWord', [FlashcardController::class, 'addWord'])
        ->name('flashcard.addWord');

    Route::get('/flashcard/{id}/quiz', [FlashcardController::class, 'startQuiz'])
        ->name('flashcard.quiz');

    Route::get('/flashcard/{id}/quizResults', [FlashcardController::class, 'getQuizResults'])
        ->name('flashcard.quizResults');

    // API
    Route::post('/api/flashcard/{id}/test/answer', [FlashcardController::class, 'logAnswer'])
        ->name('flashcard.answer');

    Route::get('/api/languages', function () {
        return \DB::table('languages')
            ->selectRaw('MIN(id) as id, name')
            ->groupBy('name')
            ->orderBy('name')
            ->get();
    });

    Route::get('/api/levels', function () {
        return QuizLevel::pluck('name', 'id');
    });
});

require __DIR__.'/auth.php';
