<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $question = Question::all();
        $question->makeHidden(['id', 'created_at', 'updated_at']);
        return response()->json($question, 200);
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'body'=>'required|string',
            'image'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('QuestionImages', 'public');
        }
        $question = Question::create([
            'body' => $request->input('body'),
            'image' => $path,
            'n_likes' => 0,
        ]);
        return response()->json([
            'message' => 'Question created successfully',
            'question' => $question->body,
            'image' => $question->image,
            'n_likes' => $question->n_likes,
        ], 201);
    }

    
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $question = Question::find($id);
        $question->delete();
        return response()->json([
            'message' => 'Question deleted successfully',
        ], 200);
    }
}
