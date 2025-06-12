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
        // $user = Auth::user();

        //Save Image
        $image = $request->file('image');
        $path = $image->store('UserImages', 'public');
        $fullPath = storage_path('app/public/' . $path);

        //Send image request to the Flask API on http://127.0.0.1:5000/predict
        $response = Http::timeout(120)->attach(
            'image',
            file_get_contents($fullPath),
            basename($fullPath)
        // )->post('http://nextechs.xyz/projects/plants/predict');
        )->post('http://127.0.0.1:5000/predict');

        $plant_type = $response->json()['plant_type'] ?? 'unknown'; // "pepper"
        $diagnosis = $response->json()['diagnosis'] ?? 'unknown';   // "healthy"
       
        // return response()->json([
        //     'Plant_type' => $plant_type,
        //     'Diagnosis' => $diagnosis,
            
        // ]);

        //Show Dataplant from plant table based on the plant type
         $results = DB::table('plants as p')
          ->where('p.common_name','LIKE',"%$plant_type%")
         ->select('p.common_name', 'p.scientific_name', 'p.plant_family', 'p.care_instructions','p.image')
         ->first();

        $plant_id = DB::table('plants')->whereRaw('LOWER(TRIM(common_name)) = ?', [strtolower(trim($plant_type))])->value('id');

//         // save the image record to the images table
        // $imageRecord = Image::create([
        //     'image_path' => $path,
        //     'plant_type' => $plant_type,
        //     'diagnosis' => $diagnosis,
        //     'plant_id' => $plant_id
        // ]);

//         // Save the image record to table my_plants
        // MyPlants::create([
        //     'user_id' => $user->id,
        //     'plant_id' => $plant_id,
        //     'image_id' => $imageRecord->id
        // ]);

        if ($results) {
            return response()->json([
                'Plant_type' => $plant_type,
                'Diagnosis' => $diagnosis,
                'Data_of_your_plant' => $results,
            ]);
        } else {
            return response()->json([
                'Plant_type' => $plant_type,
                'Diagnosis' => $diagnosis,
                'message' => 'Plant not found in database.',
            ], 200);
        }
    





//     public function delete()
//     {

//         //DB::table('images')->truncate();
//     }
//     public function imagerasberry()
//     {
    
//     }
    }
}
