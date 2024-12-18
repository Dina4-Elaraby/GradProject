<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dht22Sensor;

class Dht22SensorController extends Controller
{
   
    public function index()
    {
        return Dht22Sensor::all();
    }

    
    public function show($id)
    {
        return Dht22Sensor::findOrFail($id);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
            'reading_date' => 'required|date',
            'reading_time' => 'required|date_format:H:i:s',
        ]);

        $sensorData = Dht22Sensor::create($validatedData);

        return response()->json($sensorData, 201); 
    }

    
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'temperature' => 'sometimes|numeric',
            'humidity' => 'sometimes|numeric',
            'reading_date' => 'sometimes|date',
            'reading_time' => 'sometimes|date_format:H:i:s',
        ]);

        $sensorData = Dht22Sensor::findOrFail($id);
        $sensorData->update($validatedData);

        return response()->json($sensorData, 200);
    }

   
    public function destroy($id)
    {
        $sensorData = Dht22Sensor::findOrFail($id);
        $sensorData->delete();

        return response()->json(['message' => 'Data deleted successfully'], 200);
    }
}
