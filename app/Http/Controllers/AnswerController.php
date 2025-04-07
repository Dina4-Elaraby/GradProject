<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\Question;

class AnswerController extends Controller
{
    public function index($questionId)
    {
        $answers = Answer::where('question_id', $questionId)->get();
        return response()->json($answers);
    }

  
    public function store(Request $request, $questionId)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $question = Question::findOrFail($questionId);

        $answer = new Answer();
        $answer->body = $request->body;
        $answer->question_id = $question->id;
        $answer->save();

        return response()->json($answer, 201);
    }

   
    public function update(Request $request, $id)
    {
        $answer = Answer::findOrFail($id);

        $request->validate([
            'body' => 'required|string',
        ]);

        $answer->update($request->all());

        return response()->json($answer);
    }

  
    public function destroy($id)
    {
        $answer = Answer::findOrFail($id);
        $answer->delete();

        return response()->json(null, 204);
    }
}
