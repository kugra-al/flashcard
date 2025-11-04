<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flashcard;
use App\Models\FlashcardEntry;
use App\Models\FlashcardTestAnswer;
use Inertia\Inertia;
use Illuminate\Support\Facades\Config;
use DB;

class FlashcardController extends Controller
{

    public function startQuiz(Request $request, $id)
    {
        $flashcard = Flashcard::findOrFail($id);
        $questions = $request->questions;
        $minQuestions = Config::get('flashcard.quiz.minQuestions');
        if ($questions < $minQuestions || is_nan($questions))
            $questions = $minQuestions;

        $quizService = app(\App\Services\FlashcardTest::class);
        $quiz = $quizService->startSession($request->user(), $flashcard, $questions);

        return Inertia::render('Flashcard/Quiz', [
            'flashcardId' => $flashcard->id,
            'quiz' => $quiz,
        ]);
    }

    public function logAnswer(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'correct' => 'required|boolean',
        ]);

        $user = $request->user();

        // Save to DB or do analytics as desired
        // Example:
        FlashcardTestAnswer::create([
            'user_id' => $user->id,
            'flashcard_id' => $id,
            'question_text' => $request->question,
            'answer_text' => $request->answer,
            'correct' => $request->correct,
        ]);

        return response()->json(['message' => 'Answer logged successfully']);
    }

    public function getQuizResults(Request $request, $flashcardId)
    {
        $user = $request->user();
        $flashcard = Flashcard::findOrFail($flashcardId);
        $topCorrect = DB::table('flashcard_test_answers')
            ->select('question_text', 'answer_text', DB::raw('COUNT(*) as count'))
            ->where('user_id', $user->id)
            ->where('flashcard_id', $flashcardId)
            ->where('correct', true)
            ->groupBy('question_text', 'answer_text')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $topWrong = DB::table('flashcard_test_answers')
            ->select('question_text', DB::raw('COUNT(*) as count'))
            ->where('user_id', $user->id)
            ->where('flashcard_id', $flashcardId)
            ->where('correct', false)
            ->groupBy('question_text')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $questionTexts = $topWrong->pluck('question_text')->toArray();
        $commonWrongAnswers = DB::table('flashcard_test_answers')
            ->select('question_text', 'answer_text', DB::raw('COUNT(*) as count'))
            ->where('user_id', $user->id)
            ->where('flashcard_id', $flashcardId)
            ->where('correct', false)
            ->whereIn('question_text', $questionTexts)
            ->groupBy('question_text', 'answer_text')
            ->orderByDesc('count')
            ->get()
            ->groupBy('question_text');
        $correctAnswers = DB::table('flashcard_entries')
            ->where('flashcard_id', $flashcardId)
            ->whereIn('question', $questionTexts)
            ->pluck('answer', 'question'); // key = question_text
        $wrongResults = [];

        foreach ($topWrong as $entry) {
            $question = $entry->question_text;
            $wrongResults[] = [
                'question' => $question,
                'count' => $entry->count,
                'correct_answer' => $correctAnswers[$question] ?? 'N/A',
                'common_wrong_answers' => $commonWrongAnswers[$question] ?? collect()
            ];
        }

        return Inertia::render('Flashcard/Results', [
            'topCorrect' => $topCorrect,
            'topWrong' => $wrongResults,
            'flashcard' => $flashcard,
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

    public function addWord(Request $request, $id) {
        $validated = $request->validate([
            'question'          => 'required',
            'answer'            => 'required'
        ]);
        $validated['flashcard_id'] = $id;
        $flashcard = Flashcard::findOrFail($id);
        if ($request->user()->id !== $flashcard->user_id) {
            return response()->json(['message' => 'Unauthorized: You do not own this flashcard.'], 403);
        }
        $data = FlashcardEntry::create($validated);
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
