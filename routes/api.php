<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\DiseaseController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PlantDiseaseController;
use App\Http\Controllers\TreatmentDiseaseController;
use App\Http\Controllers\MyPlantsController;
use App\Models\Measurement;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

#region user
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:api');
Route::post('/refresh', [UserController::class, 'refresh'])->middleware('auth:api');
Route::post('/me', [UserController::class, 'me'])->middleware('auth:api');
Route::get('/search', [UserController::class, 'getUserByToken'])->middleware('auth:api');
Route::post('update_profile', [UserController::class, 'updateProfile'])->middleware('auth:api');
Route::get('Data',[UserController::class,'getData'])->middleware('CheckUser');


//Route::Put('/edit_profile', [UserController::class, 'editprofile'])->middleware('auth:api');
#endregion

#region question
// Route::post('/add_question', [QuestionController::class, 'store']);
// Route::get('/questions', [QuestionController::class, 'index']);
// Route::delete('/questions/{id}', [QuestionController::class, 'destroy']);
#endregion

#region answer
// Route::post('/add_answer',[AnswerController::class,'store']);
// Route::Get('/show_answer',[AnswerController::class,'index']);
// Route::delete('/delete',[AnswerController::class,'destory']);
#endregion

#region plant
Route::post('/add_plant', [PlantController::class, 'store']);
Route::get('/show_plant', [PlantController::class, 'index']);
Route::delete('/delete_plant/{id}', [PlantController::class, 'destroy']);
Route::post(('/update_plant/{id}'), [PlantController::class, 'update']);
#endregion

#region diseases
Route::post('/add_disease', [DiseaseController::class, 'store']);
Route::get('/show_disease', [DiseaseController::class, 'index']);
Route::delete('disease/{id}', [DiseaseController::class, 'destroy']);
Route::get('/disease/search', [DiseaseController::class, 'search']);
Route::delete('/delete_disease/{id}', [DiseaseController::class, 'remove']);
#endregion

#region plant_disease
Route::post('/add_plant_disease', [PlantDiseaseController::class, 'store']);
Route::get('/show_plant_disease', [PlantDiseaseController::class, 'index']);
Route::post('/plant_disease', [PlantDiseaseController::class, 'GetdiseaseByPlant']);
#endregion

#region my_plants
Route::post('/add_myplant', [MyPlantsController::class, 'store']);
Route::get('/show_myplant', [MyPlantsController::class, 'index']);
Route::delete('/delete_myplant/{id}', [MyPlantsController::class, 'destroy']);
#endregion

#region treatment
Route::post('/add_treatment', [TreatmentController::class, 'store']);
Route::get('/show_treatment', [TreatmentController::class, 'index']);
Route::delete('/delete_treatment/{id}', [TreatmentController::class, 'destroy']);
#endregion

#region treatment_disease
Route::post('/add_treatment_disease', [TreatmentDiseaseController::class, 'store']);
Route::get('/show_treatment_disease', [TreatmentDiseaseController::class, 'index']);
Route::post('/treatment_disease', [TreatmentDiseaseController::class, 'GetTreatmentByDiseaseName']);
#endregion

#region image
Route::post('/upload_image', [ImageController::class, 'uploadAndCheck']);
Route::delete('/delete', [ImageController::class, 'delete']);
#endregion


Route::resource('questions', QuestionController::class);
Route::resource('answers', AnswerController::class);









Route::post('/clear-cache', function (Request $request) {
    if ($request->input('key') !== '123456') {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    return response()->json(['message' => 'Cache cleared successfully']);
});

