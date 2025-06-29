<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Plant;
use App\Models\MyPlants;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;


class ImageController extends Controller
{

    public function uploadAndCheck(Request $request)
    {
        // $request->validate([
        //     'image' => 'required|image|max:5120',
        // ]);

    
        //     //Send image request to the Flask API on http://127.0.0.1:5000/predict
        // $response = Http::timeout(120)->attach(
        //     'image',
        //     file_get_contents($full),
        //     basename($full)
        // )->post('http://127.0.0.1:5000/predict');
        // $plant_type = $response->json()['plant_type'] ?? 'unknown'; // "pepper"
        // $diagnosis = $response->json()['diagnosis'] ?? 'unknown';   // "healthy"
       
    //     return response()->json([
    //         'Plant_type' => $plant_type,
    //         'Diagnosis' => $diagnosis,
            
    //     ]);
    #region withoutflask
    // $process = new Process(['python', base_path('test_flask_ai_api/onxxTestingWithoutFlask.py'), $full]);
    // $process->setTimeout(120);
    // $process->run();

    // if (!$process->isSuccessful())
    // {
    //     throw new ProcessFailedException($process);
    // }
    // $output = $process->getOutput();
    // $lines = explode("\n", trim($output));
    // $lastout = end($lines);

    // $response = json_decode($lastout, true);
    // if (is_null($response))
    //  {
    // return response()->json([
    //     'error' => 'JSON parse failed',
    //     'raw_output' => $lastout, 
    // ], 500);
    // }
    // $plant_type = $response['plant_type'] ?? 'unknown'; // "pepper"
    // $diagnosis = $response['diagnosis'] ?? 'unknown'; // "healthy"
       
       
    #endregion       
    
     }

private function sendImageToFlask(Request $request)
{
    $request->validate([
        'image' => 'required|image|max:5120',
    ]);

    //Check if the user is authenticated
    //  $user = Auth::user();
     //Save Image
        $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('UploadImage', $imageName, 'public');
            $full = storage_path('app/public/UploadImage/' . $imageName );
            $imageUrl = asset('storage/UploadImage/'.$imageName);

    // $image = $request->file('image');
    // $fullPath = $image->getPathname();

    $response = Http::timeout(120)->attach(
        'image',
        file_get_contents($full),
        $imageName
    )->post('http://127.0.0.1:5000/predict');

    if (!$response->successful()) {
        abort(500, 'Failed to contact Flask API');
    }
return array_merge($response->json(), [
        'image' => 'storage/UploadImage/' . $imageName
    ]);
    return $response->json();
}

public function getTop3Predictions(Request $request)
{
    $data = $this->sendImageToFlask($request);

    //  $sorted = collect($data['top3'])
    //     ->sortByDesc(function ($value) {
    //         return (float) str_replace('%', '', $value);
    //     });
$top3 = $data['top3'];
$sorted = collect($top3)->sortByDesc(function ($value) {
    return (float) str_replace('%', '', $value);
})->toArray(); 
$plant_type = $data['plant_type'] ;

$second_prediction = array_key_first(array_slice($sorted, 1, 1, true));
$third_prediction = array_key_first(array_slice($sorted, 2, 1, true));


$plant_id = DB::table('plants')
    ->whereRaw('LOWER(TRIM(common_name)) = ?', [strtolower(trim($plant_type))])
    ->value('id');

$image = Image::create([
    'image_path' => $data['image'],
    'plant_type' => $data['plant_type'],
    'plant_id' => $plant_id,
    'second_prediction' => $second_prediction,
    'third_prediction' => $third_prediction,
]);
   return response()->json([
    'top3' => $sorted,
   ]);
   
}
 


public function getDiagnosis(Request $request)
{
    $data = $this->sendImageToFlask($request);
    $diagnosis = $data['diagnosis'];
    $path = $data['image'];
    $plant_type = $data['plant_type'];


   #region Show Dataplant from plant table based on the plant type
    $plant_id = DB::table('plants')
    ->whereRaw('LOWER(TRIM(common_name)) = ?', [strtolower(trim($plant_type))])
    ->value('id');

     $results = DB::table('plants as p')
    ->where('p.id', $plant_id)
    ->select('p.common_name', 'p.scientific_name', 'p.plant_family', 'p.care_instructions')
    ->first();
    #endregion
    
    #region save the image record to the images table
$imageRecord = Image::create([
    'image_path' => $path,
    'plant_type' => $plant_type,
    // 'second_prediction' => $data['second_prediction'] ?? null,
    // 'third_prediction' => $data['third_prediction'] ?? null,
    'diagnosis' => $diagnosis,
    'plant_id' => $plant_id,
]);
     #endregion

     #region update
// $image = Image::where('image_path', $path)->latest()->first();

//     if ($image) {
//         $image->update([
//             'diagnosis' => $data[$diagnosis],
//             'plant_type' => $plant_type,
//             'plant_id' => $plant_id
//         ]);
//     }
#endregion

    #region Save the image record to table images
    $imageRecord = Image::create([
        'image_path' => $path,
        'plant_type' => $plant_type,
        'second_prediction' => $data['second_prediction'] ?? null,
        'third_prediction' => $data['third_prediction'] ?? null,
        'diagnosis' => $diagnosis,
        'plant_id' => $plant_id,
    ]);
    #endregion

#region Save the image record to table my_plants
        // $user = Auth::user();
        // MyPlants::create([
        //     'user_id' => 23,
        //     'plant_id' => $plant_id,
        //     'image_id' => $imageRecord->id
        // ]);
#endregion

    return response()->json([
        'plant_type' => $data['plant_type'],
        'diagnosis' => $data['diagnosis'],
        'plant_details' => $results,
    ]);
}


public function getTreament(Request $request)
{
    $data = $this->sendImageToFlask($request);
    $plant_type = $this->sendImageToFlask($request)['plant_type'];
    $diagnosisRaw = $data['diagnosis'];


    
    $cleanDiagnosis = str_replace(['_', ','], ' ', $diagnosisRaw);

    
    $diseases = DB::table('diseases')->get();

    $bestMatch = null;
    $bestPercent = 0;

    foreach ($diseases as $disease) {
        similar_text(strtolower($cleanDiagnosis), strtolower($disease->name), $percent);
        if ($percent > $bestPercent) {
            $bestPercent = $percent;
            $bestMatch = $disease;
        }
    }

    if ($bestMatch && $bestPercent > 60) 
    {

            $treatment = DB::table('treatment_diseases as td')
            ->join('treatments as t', 'td.treatment_id', '=', 't.id')
            ->where('td.disease_id', $bestMatch->id)
            ->select('t.description')
            ->first();

        return response()->json([
            'plant_type' => $plant_type,
            'diagnosis' => $cleanDiagnosis,
            'treatment' => $treatment

        ]);
    }

    return response()->json([
        'plant_type' => $plant_type,
        'diagnosis' => $cleanDiagnosis,
        'treatment' => 'The plant is healthy no treatment needed.',
    ]);


}

}