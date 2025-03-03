<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plant;


class PlantController extends Controller
{
    public function index()
    {
      return Plant::all();
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate
        ([
            'scientific_name' => 'required|string|max:255',
            'common_name' => 'required|string',
            'plant_family' => 'nullable|string',
            'care_instructions' => 'nullable|string',
           
        ]);
       
        Plant::create($validatedData);
        return response()->json($validatedData, 201);

    }

    public function show(string $id)
    {
        $plant = Plant::find ($id);
        if(!$plant)
        {
            return response()->json(['message'=>"plant not found "],404);
        }
        return $plant;
    }


    public function update(Request $request, string $id)
    {
        $plant = Plant::find ($id);
        if(!$plant)
        {
            return response()->json(['message'=>"plant not found "],404);
        }
        $plant ->update($request->all());
        return response()->json($plant,200);
    }

    public function destroy(string $id)
    {
        $plant = Plant::find ($id);
        if(!$plant)
        {
            return response()->json(['message'=>"plant not found "],404);
        }
        $plant ->delete();
        return response()->json(['message'=>'The plant is deleted'],200);
    }
}
