<?php

namespace App\Http\Controllers;

use App\Models\MyPlants;
use App\Models\Image;
use Illuminate\Http\Request;


class MyPlantsController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // public function index()
    // {
    //     $myPlants = MyPlants::with(['user', 'plant', 'image'])->get();

    //     return response()->json($myPlants);
    // }

//     public function index()
// {
//     $myPlants = MyPlants::with(['user', 'plant', 'image'])->get();

//     $data = $myPlants->map(function ($item) {
//         return [
//             // 'id' => $item->id,
//             // 'user' => $item->user?->name,
//             // 'plant' => $item->plant?->common_name,
//             // 'image_path' => $item->image?->image_path,
//             'plant_type' => $item->image?->plant_type,
//             'diagnosis' => $item->image?->diagnosis,
//             'treatment' => $item->treatment, 
//             'created_at' => $item->created_at,
//         ];
//     });

//     return response()->json($data);
// }

// public function index()
// {
//     $lastImage = Image::latest()->first();
//     if (!$lastImage) 
//     {
//         return response()->json(['message' => 'No image'], 404);
//     }
//     $myPlant = MyPlants::where('image_id', $lastImage->id)->with(['plant', 'user'])->first();

//     return response()->json([
//         'plant_type' => $lastImage->plant_type,
//         'Second Prediction' => $lastImage->second_predictions,
//         'Third Prediction' => $lastImage->third_predictions,
//         'diagnosis' => $lastImage->diagnosis,
//         'treatment' => $myPlant?->treatment ?? 'No treatment available',
//         'created_at' => $lastImage->created_at,
//     ]);
// }


// public function index()
// {
//     $lastImage = Image::latest()->first();

//     if (!$lastImage) {
//         return response()->json(['message' => 'No image found'], 404);
//     }

//     $myPlant = MyPlants::where('image_id', $lastImage->id)->with(['plant', 'user'])->first();

//     $response = [
//         'plant_type' => $lastImage->plant_type,
//         'Second Prediction' => $lastImage->second_prediction,
//         'Third Prediction' => $lastImage->third_prediction,
//         'created_at' => $lastImage->created_at,
//     ];

   
//     if (!empty($lastImage->diagnosis)) {
//         $response['diagnosis'] = $lastImage->diagnosis;
//     }

//     return response()->json($response);
// }

public function index()
{
    $lastImage = Image::latest()->first();

    if (!$lastImage) 
    {
        return response()->json(['message' => 'No image found'], 404);
    }

    if (!empty($lastImage->diagnosis)) {
        return response()->json([
            'plant_type' => $lastImage->plant_type,
            'diagnosis' => $lastImage->diagnosis,
            'created_at' => $lastImage->created_at,
        ]);
    }


    if (!empty($lastImage->second_prediction)) {
        return response()->json([
            'plant_type' => $lastImage->plant_type,
            'Second Prediction' => $lastImage->second_prediction,
            'Third Prediction' => $lastImage->third_prediction,
            'created_at' => $lastImage->created_at,
        ]);
    }

   
}

    
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id,default:23',
            'plant_id' => 'required|exists:plants,id',
            'image_id' => 'required|exists:images,id',
        ]);

        MyPlants::create($request->all());

        return response()->json(['message' => 'Plant added successfully'], 201);
    }

    
    public function destroy(MyPlants $myPlants)
    {
        $myPlants->delete();

        return response()->json(['message' => 'Plant removed successfully'], 200);
    }
}
