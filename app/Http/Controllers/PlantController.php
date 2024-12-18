<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plant;


class PlantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      return Plant::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $plant = Plant::create ($request->all());
        return response()->json($plant,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $plant = Plant::find ($id);
        if(!$plant)
        {
            return response()->json(['message'=>"plant not found "],404);
        }
        return $plant;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
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
