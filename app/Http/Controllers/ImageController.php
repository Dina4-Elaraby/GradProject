<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Plant;


class ImageController extends Controller
{
    
    public function uploadAndCheck(Request $request)
    {
    $request->validate([
        'image' => 'required|image|max:5120', 
    ]);
    
    $image = $request->file('image');
    $path = $image->store('UserImages', 'public');
    $fullPath = storage_path('app/public/' . $path);

    $response = Http::attach(
        'image',
        file_get_contents($fullPath),
        basename($fullPath)
    )->post('http://127.0.0.1:5000/predict');

    $plantName = $response->json()['result'] ?? 'unknown'; //Pepper,_bell___Bacterial_spot
    $plant_type = explode('___', $plantName)[0]; //Pepper,_bell
    $plant_type = str_replace(',_bell', ' ', $plant_type); //Pepper

    $results = DB::table('plants as p')
    ->where('p.common_name', $plant_type)
    ->select('p.common_name', 'p.scientific_name', 'p.plant_family', 'p.care_instructions')
    ->first();

    $plant_id = DB::table('plants')->where('common_name', $plant_type)->value('id');

    $imageRecord = Image::create([
        'image_path' => $path,
        'plant_type' => $plant_type,
        'diagnosis' => $plantName,
        'plant_id' => $plant_id 
    ]);

    if ($results) {
        return response()->json([
            'Plant_type'=>$plant_type,
            'Diagnosis'=> $plantName,
            'Data_of_your_plant'=>$results,       
        ]);
    } else {
        return response()->json([
            'Plant_type' => $plant_type,
            'Diagnosis' => $plantName,
            'message' => 'Plant not found in database.',
        ],200);
    }
}
public function delete()
{
    DB::table('images')->truncate();
}
public function remove()
{

}
    }

