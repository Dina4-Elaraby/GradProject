<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Temp;

class TempController extends Controller
{
    public function store(Request $request)
    {
        if (!$request->hasFile('image_path')) 
        {
            return response()->json(['error' => 'No image file received.'], 400);
        }

        $request->validate([
            'image_path' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $path = $request->file('image_path')->store('temp', 'public');
        
        Temp::create
        ([
            
            'image_path' => $path, 
        ]);

        return response()->json
        ([
            'message' => 'Image uploaded successfully',
            'image_url' => asset("storage/{$path}"),
        ], 201);

     
}
   
}

