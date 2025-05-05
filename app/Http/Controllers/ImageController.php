<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Plant;
use App\Models\MyPlants;


class ImageController extends Controller
{
    public function uploadAndCheck(Request $request)

    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        // Check if the user is authenticated
        $user = Auth::user();

        //Save Image
        $image = $request->file('image');
        $path = $image->store('UserImages', 'public');
        $fullPath = storage_path('app/public/' . $path);

        //Send image request to the Flask API on http://127.0.0.1:5000/predict
        $response = Http::attach(
            'image',
            file_get_contents($fullPath),
            basename($fullPath)
        )->post('http://127.0.0.1:5000/predict');


        $plantName = $response->json()['result'] ?? 'unknown'; //Pepper,_bell___Bacterial_spot
        $plant_type = explode('___', $plantName)[0]; //Pepper,_bell
        $plant_type = str_replace(',_bell', ' ', $plant_type); //Pepper

        //Show Dataplant from plant table based on the plant type
        $results = DB::table('plants as p')
            ->where('p.common_name', $plant_type)
            ->select('p.common_name', 'p.scientific_name', 'p.plant_family', 'p.care_instructions')
            ->first();

        $plant_id = DB::table('plants')->where('common_name', $plant_type)->value('id');

        // save the image record to the images table
        $imageRecord = Image::create([
            'image_path' => $path,
            'plant_type' => $plant_type,
            'diagnosis' => $plantName,
            'plant_id' => $plant_id
        ]);

        // Save the image record to table my_plants
        MyPlants::create([
            'user_id' => $user->id,
            'plant_id' => $plant_id,
            'image_id' => $imageRecord->id
        ]);

        if ($results) {
            return response()->json([
                'Plant_type' => $plant_type,
                'Diagnosis' => $plantName,
                'Data_of_your_plant' => $results,
            ]);
        } else {
            return response()->json([
                'Plant_type' => $plant_type,
                'Diagnosis' => $plantName,
                'message' => 'Plant not found in database.',
            ], 200);
        }
    }


    public function delete()
    {

        //DB::table('images')->truncate();
    }
    public function imagerasberry()
    {
    
    }
}
