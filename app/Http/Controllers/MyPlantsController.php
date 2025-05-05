<?php

namespace App\Http\Controllers;

use App\Models\MyPlants;
use Illuminate\Http\Request;

class MyPlantsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $myPlants = MyPlants::with(['user', 'plant', 'image'])->get();

        return response()->json($myPlants);
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'plant_id' => 'required|exists:plants,id',
            'image_id' => 'required|exists:images,id',
        ]);

        MyPlants::create($request->all());

        return response()->json(['message' => 'Plant added successfully'], 201);
    }

    
    public function destroy(MyPlants $myPlants)
    {
        $myPlants->delete();

        return response()->json(['message' => 'Plant removed successfully'], 200);
    }
}
