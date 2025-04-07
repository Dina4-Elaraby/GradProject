<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Temp;
use App\Models\Plant;

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
            'plant_id' => 'required|exists:plants,id',
        ]);

        $path = $request->file('image_path')->store('temp', 'public');
        
        Temp::create
        ([
            
            'image_path' => $path, 
            'plant_id' => $request->plant_id,
        ]);

        return response()->json
        ([
            'message' => 'Image uploaded successfully',
            'image_url' => asset("storage/{$path}"),
        ], 201);

     
}

public function getImages($plant_id)
{
    $images = Temp::where('plant_id', $plant_id)->get();
    return response()->json($images);
}
   
}

