<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with('user')->get();

        $formatted = $questions->map(function ($question) {
            return [
                'id'    => $question->id,
              'likes' => $question->likes,

                'body'  => $question->body,
                'image' => $question->image ? url($question->image) : null,
                'user'  => [
                    'id'   => $question->user->id,
                    'name' => $question->user->name,
                ],
                'created_at' => $question->created_at,
                'updated_at' => $question->updated_at,
            ];
        });

        return response()->json($formatted);
        
    }

    public function show($id)
    {
        $question = Question::with(['answers', 'user'])->findOrFail($id);

        return response()->json([
            'id'    => $question->id,
         'likes' => $question->likes,

            'body'  => $question->body,
            'image' => $question->image ? url($question->image) : null,
            'user'  => [
                'id'   => $question->user->id,
                'name' => $question->user->name,
            ],
            'answers' => $question->answers, // ممكن تعدلي ده لو عايزة تنسقيه برضو
            'created_at' => $question->created_at,
            'updated_at' => $question->updated_at,
        ]);
    }

    public function store(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        $request->validate([
       
            'body' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $question = new Question();
        $question->user_id = $user->id;
    
        $question->body = $request->body;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('question_images', 'public');
            $question->image = 'storage/' . $path;
        }

        $question->save();
        $question->load('user');

        return response()->json([
            'id'    => $question->id,
      
            'body'  => $question->body,
            'image' => $question->image ? url($question->image) : null,
            'user'  => [
                'id'   => $question->user->id,
                'name' => $question->user->name,
            ],
            'created_at' => $question->created_at,
            'updated_at' => $question->updated_at,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);

        $request->validate([
       
            'body' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $question->body = $request->body;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('question_images', 'public');
            $question->image = 'storage/' . $path;
        }

        $question->save();
        $question->load('user');

        return response()->json([
            'id'    => $question->id,
    
            'body'  => $question->body,
            'image' => $question->image ? url($question->image) : null,
            'user'  => [
                'id'   => $question->user->id,
                'name' => $question->user->name,
            ],
            'created_at' => $question->created_at,
            'updated_at' => $question->updated_at,
        ]);
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return response()->json(null, 204);
    }

    public function getImage($filename)
    {
        $path = storage_path('app/public/question_images/' . $filename);

        if (file_exists($path)) {
            return response()->json([
                'image_url' => url('storage/question_images/' . $filename)
            ]);
        }

        return response()->json(['error' => 'Image not found'], 404);
    }
    public function like($id)
{
    $question = Question::findOrFail($id);
    $question->increment('likes'); // تزود اللايك 1
    return response()->json([
        'message' => 'Liked!',
        'likes' => $question->likes
    ]);
}
public function unlike($id)
{
    $question = Question::findOrFail($id);
    if ($question->likes > 0) {
        $question->decrement('likes'); // تقلل اللايك 1
    }
    return response()->json([
        'message' => 'Unliked!',
        'likes' => $question->likes
    ]);
}

}
























