<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\DiseaseController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\Dht22SensorController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PlantDiseaseController;

#region user
Route::post('/register', [userController::class, 'register']);
Route::post('/login', [userController::class, 'login']);
Route::post('/logout', [userController::class, 'logout'])->middleware('auth:api');
Route::post('/refresh', [userController::class, 'refresh'])->middleware('auth:api');
Route::post('/me', [userController::class, 'me'])->middleware('auth:api');
Route::get('/search', [UserController::class, 'getUserByToken'])->middleware('auth:api');
#endregion

#region question
Route::post('/add_question', [QuestionController::class, 'store']);
Route::get('/questions', [QuestionController::class, 'index']);
Route::delete('/questions/{id}', [QuestionController::class, 'destroy']);
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












//Route::resource('plants', PlantController::class)->except(['create' , 'edit']);
Route::resource('treatments', TreatmentController::class)->except(['create' , 'edit']);
Route::resource('sensors', Dht22SensorController::class);
Route::get('Data',[UserController::class,'getData'])->middleware('CheckUser');
Route::post('/upload_image', [ImageController::class, 'uploadAndCheck']);
Route::delete('/delete', [ImageController::class, 'delete']);
Route::middleware(['auth:api'])->group(function () 
{   
Route::post('device', [DeviceController::class, 'store']);
Route::get('device', [DeviceController::class, 'index']);
});
Route::apiResource('sensor', SensorController::class);

