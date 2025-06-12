<?php

namespace App\Http\Controllers;

use App\Models\UserQuestionAnswer;
use Illuminate\Http\Request;

class UserQuestionAnswerController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'questioner_id' => 'required|exists:users,id',
            'answerer_id' => 'required|exists:users,id',
            'question_id' => 'required|exists:questions,id',
            'answer_id' => 'required|exists:answers,id',
        ]);

        $relation = UserQuestionAnswer::create($data);

        return response()->json(['data' => $relation], 201);
    }

    public function index()
    {
        return response()->json(UserQuestionAnswer::with(['questioner', 'answerer', 'question', 'answer'])->get());
    }
}

