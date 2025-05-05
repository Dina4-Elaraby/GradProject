<?php

namespace App\Http\Controllers;
use App\Models\Measurement;

use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    //Entre data
    public function store(Request $request)
    {
        $data = $request->validate([
            'device_id' => 'required|exists:devices,id',
            'water_level' => 'required|numeric',
            'dht_humidity' => 'required|numeric',
            'dht_temperature' => 'required|numeric',
            'is_moist' => 'required|string'
        ]);
    
        return Measurement::create($data);
    }
    
    //Get data
    // public function index()
    // {
    //     return Measurement::with('device')->get();
    // }
}