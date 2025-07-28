<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\FlashcardController;
use App\Models\Country;
use App\Models\Language;
use App\Models\Flashcard;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/api/languages', function () {
    return \DB::table('languages')
        ->selectRaw('MIN(id) as id, name')
        ->groupBy('name')
        ->orderBy('name')
        ->get();
});

Route::post('/api/submit-flashcard', [FlashcardController::class, 'store'])->name('flashcard.store');

Route::get('/flashcards', function () {
    $flashcards = Flashcard::with(['primary_language', 'secondary_language', 'user', 'entries'])->get();
    return Inertia::render('Flashcard/Index', ['flashcards' => $flashcards]);
})->middleware(['auth', 'verified'])->name('flashcards');

Route::get('/flashcard/{id}', function ($id) {
    $flashcard = Flashcard::with(['primary_language', 'secondary_language', 'user', 'entries'])->findOrFail($id);
    return Inertia::render('Flashcard/Show', ['flashcard' => $flashcard]);
})->name('flashcard.show');

Route::get('/flashcard/{id}/test', [FlashcardController::class, 'start'])
    ->middleware('auth')
    ->name('flashcard.test');

require __DIR__.'/auth.php';
