<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SensorController extends Controller
{
    public function index()
    {
        return Sensor::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'DHT_ReadingDate' => 'required|date',
            'DHT_ReadingTime' => 'required|date_format:H:i:s',
            'DHT_Temperature_C' => 'required|numeric',
            'DHT_Temperature_F' => 'required|numeric',
            'DHT_Humidity' => 'required|numeric',
            'is_moist' => 'required|boolean',
            'WaterLevelStatus' => 'required|in:low,medium,high',
            'image_base64' => 'required|string', // base64 input
        ]);
    
        // Base64 to image logic
        $base64String = $request->input('image_base64');
    
        if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $type)) {
            $base64String = substr($base64String, strpos($base64String, ',') + 1);
            $type = strtolower($type[1]);
    
            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                return response()->json(['error' => 'Invalid image type'], 400);
            }
        } else {
            return response()->json(['error' => 'Invalid base64 image data'], 400);
        }
    
        $binaryData = base64_decode($base64String);
        $fileName = 'sensor_' . time() . '.' . $type;
        $filePath = 'uploads/sensors/' . $fileName;
    
        //store here
        Storage::disk('public')->put($filePath, $binaryData);
    
        // Save sensor record
        $sensor = Sensor::create([
            'DHT_ReadingDate' => $request->DHT_ReadingDate,
            'DHT_ReadingTime' => $request->DHT_ReadingTime,
            'DHT_Temperature_C' => $request->DHT_Temperature_C,
            'DHT_Temperature_F' => $request->DHT_Temperature_F,
            'DHT_Humidity' => $request->DHT_Humidity,
            'is_moist' => $request->is_moist,
            'WaterLevelStatus' => $request->WaterLevelStatus,
            'image_base64' => $filePath,
        ]);
    
        return response()->json($sensor, 201);
    
    }

    public function show($id)
    {
        return Sensor::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $sensor = Sensor::findOrFail($id);

        $validated = $request->validate([
            'DHT_ReadingDate' => 'sometimes|date',
            'DHT_ReadingTime' => 'sometimes|date_format:H:i:s',
            'DHT_Temperature_C' => 'sometimes|numeric',
            'DHT_Temperature_F' => 'sometimes|numeric',
            'DHT_Humidity' => 'sometimes|numeric',
            'is_moist' => 'sometimes|boolean',
            'WaterLevelStatus' => 'sometimes|in:low,medium,high',
            'image_base64' => 'sometimes|string',
        ]);

        $sensor->update($validated);

        return $sensor;
    }

    public function destroy($id)
    {
        Sensor::destroy($id);

        return response()->json(['message' => 'Sensor deleted successfully']);
    }
}
