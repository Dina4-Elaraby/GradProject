<?php
namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'mac_address' => 'required|string|unique:devices,mac_address',
        ]);

        // استخدم التوكين للحصول على المستخدم الحالي
        $user = Auth::user();  // هذه الطريقة ستحصل على المستخدم بناءً على التوكين

        if (!$user) {
            return response()->json([
                'message' => 'No authenticated user found.',
            ], 404);
        }

        // سجل الجهاز بناءً على المستخدم الحالي
        $device = Device::create([
            'name' => $request->name,
            'mac_address' => $request->mac_address,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Device created successfully.',
            'device' => $device,
        ], 201);
    }
}

