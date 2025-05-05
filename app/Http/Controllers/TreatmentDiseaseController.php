<?php

namespace App\Http\Controllers;

use App\Models\treatment_disease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TreatmentDiseaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $treatmentDiseases = treatment_disease::with(['disease', 'treatment'])->get();

        return response()->json($treatmentDiseases);
    }

 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'disease_id' => 'required|exists:diseases,id',
            'treatment_id' => 'required|exists:treatments,id',
        ]);

        treatment_disease::create($request->all());

        return response()->json(['message' => 'Treatment-Disease relationship created successfully.'], 201);
    }

public function GetTreatmentByDiseaseName(Request $request)
{
    $diseaseName = $request->input('name');

    $treatments = DB::table('treatment_diseases as td')
        ->join('diseases as d', 'd.id', '=', 'td.disease_id')
        ->join('treatments as t', 't.id', '=', 'td.treatment_id')
        ->where('d.name', $diseaseName)
        ->select('t.description')
        ->get();

    return response()->json($treatments);
}

    
    public function show(treatment_disease $treatment_disease)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, treatment_disease $treatment_disease)
{

}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(treatment_disease $treatment_disease)
    {
        //
    }
}
