<?php

namespace App\Http\Controllers;

use App\Models\plant_disease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlantDiseaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plantDiseases = plant_disease::with(['plant', 'disease'])->get();
        return response()->json($plantDiseases);
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'plant_id' => 'required|exists:plants,id',
            'disease_id' => 'required|exists:diseases,id',
        ]);
        plant_disease::create($request->all());
        return response()->json("plant disease is added successfully", 201);
    }

    public function GetdiseaseByPlant(Request $request)
    {
        $plantName = $request->input('name');

    $diseases = DB::table('plant_diseases as pd')
        ->join('plants as p', 'p.id', '=', 'pd.plant_id')
        ->join('diseases as d', 'd.id', '=', 'pd.disease_id')
        ->where('p.common_name', $plantName)
        ->select('d.name')
        ->get();

    return response()->json($diseases);
    }
}
    