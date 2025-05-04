<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disease;

class DiseaseController extends Controller
{
    public function index()
    {
        $diseases = Disease::all();
        return response()->json($diseases);
    }

    public function store(Request $request)
    {
        //Validate incoming data
        $validatedData = $request->validate
        ([
            'name' => 'required|string|max:255',
            'symptoms' => 'nullable|array',
            'factors' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
        ]);
        // Handle image upload
        if ($request->hasFile('image')) 
        {
            $imagePath = $request->file('image')->store('Diseaseimages', 'public');
            $validatedData['image'] = $imagePath;
        }
        
        //create a new disease
        Disease::create($validatedData);
        return response()->json(['message' => 'Disease created successfully'], 201);
    }

    public function destroy(string $id)
    {
        $disease = Disease::find($id);

        if (!$disease) 
        {
            return response()->json(['message' => 'Disease not found'], 404);
        }
        $disease->delete();
        return response()->json(['message' => 'Disease deleted successfully'], 200);
    }

    public function search(Request $request)
    {
        $query = $request->query('query');    
        $diseases = Disease::where('name', 'like', "%$query%")
            ->orWhere('id', $query)
            ->get()
            ->map(function ($disease) 
            {
                $disease->symptoms = is_string($disease->symptoms) ? json_decode($disease->symptoms) : $disease->symptoms;
                $disease->factors = is_string($disease->factors) ? json_decode($disease->factors) : $disease->factors;
               
                return $disease;
            });
        if ($diseases->isEmpty()) 
        {
            return response()->json(['message' => 'Disease not found'], 404);
        }
        return response()->json($diseases);
    }

    public function remove()
    {
        //truncate the diseases table
        Disease::truncate();
        return response()->json(['message' => 'All diseases deleted successfully from DB'], 200);
    }
    

}









