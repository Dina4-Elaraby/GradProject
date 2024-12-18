<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disease;

class DiseaseController extends Controller
{
    public function index()
    {
        $diseases = Disease::all()->map(function ($disease) {
            $disease->symptoms = is_string($disease->symptoms) ? json_decode($disease->symptoms) : $disease->symptoms;
            $disease->factors = is_string($disease->factors) ? json_decode($disease->factors) : $disease->factors;
            $disease->affected_plants = is_string($disease->affected_plants) ? json_decode($disease->affected_plants) : $disease->affected_plants;
            return $disease;
        });

        return response()->json($diseases);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'symptoms' => 'nullable|array',
            'factors' => 'nullable|array',
            'treatment' => 'nullable|string',
            'affected_plants' => 'nullable|array',
        ]);

        $data = $request->all();
        $data['symptoms'] = json_encode($request->symptoms);
        $data['factors'] = json_encode($request->factors);
        $data['affected_plants'] = json_encode($request->affected_plants);

        Disease::create($data);

        return response()->json(['message' => 'Disease created successfully'], 201);
    }

    public function destroy(string $id)
    {
        $disease = Disease::find($id);

        if (!$disease) {
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
            ->map(function ($disease) {
                $disease->symptoms = is_string($disease->symptoms) ? json_decode($disease->symptoms) : $disease->symptoms;
                $disease->factors = is_string($disease->factors) ? json_decode($disease->factors) : $disease->factors;
                $disease->affected_plants = is_string($disease->affected_plants) ? json_decode($disease->affected_plants) : $disease->affected_plants;
                return $disease;
            });
    

        if ($diseases->isEmpty()) {
            return response()->json(['message' => 'Disease not found'], 404);
        }
    
        return response()->json($diseases);
    }
    

}









