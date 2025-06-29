<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Treatment;

class TreatmentController extends Controller
{
    public function index()
    {
        $treatments = Treatment::all();
         return view('admin.treatments.index', compact('treatments'));
         
    }

     public function create()
    {
        return view('admin.treatments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
           
            'description' => 'required|string',
            
        ]);

        // Create a new treatment record
      $treatment =Treatment::create ($request->all());
      
      return redirect()->route('admin.treatments.index')->with('success', 'Treatment created.');
      return response()->json($treatment,201);
    }
      
    public function show(string $id)
    {
        $treatment = Treatment::find ($id);
        if(!$treatment)
        {
            return response()->json(['message'=>"treatment not found "],404);
        }
        return $treatment;
    }
   
    public function update(Request $request, string $id)
    {
        $treatment = Treatment::find ($id);
        if(!$treatment)
        {
            return response()->json(['message'=>"treatment not found "],404);
        }
        $treatment ->update($request->all());
        return response()->json($treatment,200);
    }

    public function destroy($id)
    {
        $treatment = Treatment::find($id);
        if (!$treatment)
        {
            return response()->json(['message' => 'Treatment not found'], 404);
        }
        $treatment->delete();
        return response()->json(['message' => 'the Treatment is deleted'], 200); 
    }

    public function edit($id)
{
    $treatment = Treatment::findOrFail($id);
    return view('admin.treatments.edit', compact('treatment'));
}

public function updateDashboard(Request $request, $id)
{
    $treatment = Treatment::findOrFail($id);
    $treatment->description = $request->description;
    $treatment->save();

    return redirect()->route('admin.treatments.index')->with('success', 'Treatment updated successfully.');
}

public function destroyDashboard($id)
{
    $treatment = Treatment::findOrFail($id);
    $treatment->delete();

    return redirect()->route('admin.treatments.index')->with('success', 'Treatment deleted successfully.');
}

 
}



