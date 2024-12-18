<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Treatment;

class TreatmentController extends Controller
{
    public function index()
    {
        return Treatment::all();
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
       

            $treatment =Treatment::create ($request->all());
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
    public function edit(string $id)
    {
        //
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

        if (!$treatment) {
            return response()->json(['message' => 'Treatment not found'], 404);
        }

        $treatment->delete();

        return response()->json(['message' => 'the Treatment is deleted'], 200);
  
    
    }

    
}



