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

#region user
Route::post('/register', [userController::class, 'register']);
Route::post('/login', [userController::class, 'login']);
Route::post('/logout', [userController::class, 'logout'])->middleware('auth:api');
Route::post('/refresh', [userController::class, 'refresh'])->middleware('auth:api');
Route::post('/me', [userController::class, 'me'])->middleware('auth:api');
Route::get('/search', [UserController::class, 'getUserByToken'])->middleware('auth:api');
#endregion

Route::post('/upload_image', [ImageController::class, 'uploadAndCheck']);
Route::delete('/delete', [ImageController::class, 'delete']);


Route::resource('plants', PlantController::class)->except(['create' , 'edit']);
Route::resource('treatments', TreatmentController::class)->except(['create' , 'edit']);
Route::resource('sensors', Dht22SensorController::class);

Route::get('/diseases', [DiseaseController::class, 'index']);
Route::post('/diseases', [DiseaseController::class, 'store']);
Route::delete('diseases/{id}', [DiseaseController::class, 'destroy']);
Route::get('/diseases/search', [DiseaseController::class, 'search']);





Route::get('Data',[UserController::class,'getData'])->middleware('CheckUser');



Route::middleware(['auth:api'])->group(function () 
{   
Route::post('device', [DeviceController::class, 'store']);
Route::get('device', [DeviceController::class, 'index']);
});

Route::resource('questions', QuestionController::class);
Route::resource('questions.answers', AnswerController::class);

Route::apiResource('sensor', SensorController::class);

