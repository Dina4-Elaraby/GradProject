<?php
namespace App\Http\Controllers;
use App\Models\Answer;
use Illuminate\Http\Request;
use App\Models\UserQuestionAnswer;
use App\Models\Question; // لازم نضيف الموديل بتاع السؤال
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
   

public function store(Request $request)
{
    // Validate the request
    $request->validate([
        'body' => 'required|string',
        'question_id' => 'required|exists:questions,id',
    ]);

    $user = Auth::user(); // Get authenticated user

    // 1. Create the answer
    $answer = Answer::create([
        'body' => $request->body,
        'question_id' => $request->question_id,
        'verified_answer' => $user->role === 'expert',
        'user_id' => $user->id,
    ]);

    // 2. Get question owner
    $question = Question::findOrFail($request->question_id);

    // 3. Create the relation in user_question_answers
    UserQuestionAnswer::create([
        'questioner_id' => $question->user_id, // اللي سأل
        'answerer_id' => $user->id,            // اللي جاوب
        'question_id' => $question->id,
        'answer_id' => $answer->id,
    ]);

    return response()->json($answer, 201);
}

    

    /**
     * Display a listing of the answers (optionally filtered by question_id).
     */
    public function index(Request $request)
    {
        $questionId = $request->query('question_id');

        if ($questionId) {
            $answers = Answer::where('question_id', $questionId)->get();
        } else {
            $answers = Answer::all();
        }

        return response()->json($answers);
    }

    /**
     * Display a single answer.
     */
    public function show($id)
    {
        $answer = Answer::findOrFail($id);
        return response()->json($answer);
    }

    /**
     * Update an existing answer.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $answer = Answer::findOrFail($id);
        $answer->body = $request->body;
        $answer->save();

        return response()->json($answer);
    }

    /**
     * Remove the specified answer.
     */
    public function destroy($id)
    {
        $answer = Answer::findOrFail($id);
        $answer->delete();

        return response()->json(['message' => 'Answer deleted successfully']);
    }
}
