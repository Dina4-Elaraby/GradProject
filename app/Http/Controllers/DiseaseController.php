<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disease;

class DiseaseController extends Controller
{
    public function index()
    {
        $diseases = Disease::all();
        return view('admin.diseases.index', compact('diseases'));
        return response()->json($diseases);
    }

    public function create()
    {
        return view('admin.diseases.create');
    }

    public function store(Request $request)
    {
        //Validate incoming data
        $validatedData = $request->validate
        ([
            'name' => 'required|string|max:255',
            'symptoms' => 'nullable|array',
            'factors' => 'nullable|array',
           
            
        ]);
       
        
        //create a new disease
        Disease::create($validatedData);
        return response()->json(['message' => 'Disease created successfully'], 201);
         return redirect()->route('admin.diseases')->with('success', 'Disease added successfully.');
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
    
// DiseaseController.php

public function edit($id)
{
    $disease = Disease::findOrFail($id);
    return view('admin.diseases.edit', compact('disease'));
}

public function update(Request $request, $id)
{
    $disease = Disease::findOrFail($id);
    $disease->name = $request->name;
    $disease->symptoms = explode(',', $request->symptoms);
    $disease->factors = explode(',', $request->factors);
    $disease->save();

    return redirect()->route('admin.diseases.index')->with('success', 'Disease updated successfully.');
}

public function destroyDashboard($id)
{
    $disease = Disease::findOrFail($id);
    $disease->delete();

    return redirect()->route('admin.diseases.index')->with('success', 'Disease deleted successfully.');
}

}









