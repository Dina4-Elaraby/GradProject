<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;

class DeviceController extends Controller
{
    public function index()
    {
        return response()->json(Device::all());
    }

    public function store(Request $request)
    {
        $request->validate
        ([
            'name_device' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
          
        ]);

        $device = Device::create
        ([
            'name' => $request->name,
            'user_id' => $request->user_id,
        ]);
        return response()->json($device, 201);
    }

    public function show(Device $device)
    {
        return response()->json($device);
    }

    public function update(Request $request, Device $device)
    {
        $request->validate
        ([
            'name_device' => 'required|string|max:255',  
            'user_id' => 'required|exists:users,id',
        ]);

        $device->update($request->all());
        return response()->json($device);
    }

    public function destroy(Device $device)
    {
        $device->delete();
        return response()->json(null, 204);
    }
}
