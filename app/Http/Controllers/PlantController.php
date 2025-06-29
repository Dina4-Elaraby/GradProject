<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plant;
use Intervention\Image\Facades\Image;


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
           
            'common_name' => 'required|string',
            'scientific_name' => 'required|string|max:255',
            'plant_family' => 'nullable|string',
            'care_instructions' => 'nullable|string',
           
        ]);
       
        Plant::create($validatedData);
        return redirect()->route('admin.plants')->with('success', 'Plant added successfully!');
        return response()->json($validatedData, 201);

    }
    public function create()
{
    return view('admin.plants.create_plant');
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


    public function update(Request $request, int $id)
    {
        $validatedData = $request->validate([
            'scientific_name' => 'sometimes|string|max:255',
            'common_name' => 'sometimes|string',
            'plant_family' => 'nullable|string',
            'care_instructions' => 'sometimes|nullable|string',
            'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $plant = Plant::find($id);
        if (!$plant) {
            return response()->json(['message' => "plant not found "], 404);
        }

        if ($request->hasFile('image'))
         {
            // $request->file('image')->store('PlantHealthyy', 'public');
            // $url = asset('storage/PlantHealthy/' . $request->file('image'));
            // $plant->image = $url;

            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('PlantHealthy', $imageName, 'public');
            $full = storage_path('app/public/PlantHealthy/' . $imageName);
            $plant->image = asset('storage/PlantHealthy/'.$imageName);


            // $imageName = time() . '.' . $request->image->extension();
            // $request->image->storeAs('PlantHealthy', $imageName, 'public');
            // $full = storage_path('app/public/PlantHealthy/' . $imageName);
            // $plant->image = url('storage/PlantHealthy/' . $imageName); // Adjusted to use 'storage' for public access
           

            // $imageName = time() . '.' . $request->image->extension();
            // $request->image->store('PlantHealthy', 'public');
            // $full=storage_path('app/public/PlantHealthy/' . $imageName);
            // $plant->image = url('app/storage/app/public/PlantHealthy/'. $imageName);     
          }

        $plant->fill($request->only([
            'scientific_name',
            'common_name',
            'plant_family',
            'care_instructions',
            
        ]));

        $plant->save();
       
            return response()->json($plant, 200);
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

    public function showPlantsInDashboard()
    {
        $plants = Plant::all();
        return view('admin.plants.show', compact('plants'));
    }
        public function edit($id)
{
    $plant = Plant::findOrFail($id);
    return view('admin.plants.edit', compact('plant'));
}

public function updateDashboard(Request $request, $id)
{
    $plant = Plant::findOrFail($id);
    $plant->update($request->all());
    return redirect()->route('admin.plants.show')->with('success', 'Plant updated successfully');
}

public function destroyDashboard($id)
{
    $plant = Plant::findOrFail($id);
    $plant->delete();
    return redirect()->route('admin.plants.show')->with('success', 'Plant deleted successfully');
}

}
