<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flashcard;
use Inertia\Inertia;

class FlashcardController extends Controller
{

    public function start(Request $request, $id)
    {
        $flashcard = Flashcard::findOrFail($id);
        $questions = $request->questions;

        $quizService = app(\App\Services\FlashcardTest::class);
        $quiz = $quizService->startSession($request->user(), $flashcard, $questions);

        return Inertia::render('Flashcard/Test', [
            'quiz' => $quiz,
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'               => 'required|max:255',
            'primary_language'   => 'required',
            'secondary_language' => 'required'
        ]);
        $validated['user_id'] = $request->user()->id;
        $data = Flashcard::create($validated);
        return response()->json(['message' => 'Success', 'data' => $data]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
