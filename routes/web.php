<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\DiseaseController;
use App\Http\Controllers\TreatmentController;
use App\Models\Disease;


Route::get('/login', function () {
    return view('admin.users.login');
})->name('login');


Route::post('/login', [UserController::class, 'loginDashboard'])->name('admin.users.login');

Route::prefix('')->middleware(['auth', 'is_admin'])->group(function () {
    


 #region  Admin routes
 Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
 #endregion

#region Plant routes
Route::get('/admin/plants', [PlantController::class, 'showPlantsInDashboard'])->name('admin.plants.show');
Route::get('/admin/plants/create', [PlantController::class, 'create'])->name('admin.plants.create');
Route::post('/admin/plants', [PlantController::class, 'store'])->name('admin.plants.store');

Route::get('/admin/plants/{id}/edit', [PlantController::class, 'edit'])->name('admin.plants.edit');
Route::put('/admin/plants/{id}', [PlantController::class, 'updateDashboard'])->name('admin.plants.update');
Route::delete('/admin/plants/{id}', [PlantController::class, 'destroyDashboard'])->name('admin.plants.destroy');

    #endregion

 #region User routes
Route::get('/admin/users', [UserController::class, 'showUsersInDashboard'])->name('admin.users.show');
Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
Route::post('admin/users/', [UserController::class, 'store'])->name('admin.users.store');

Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

#endregion

#region Disease routes 
Route::get('/admin/diseases', [DiseaseController::class, 'index'])->name('admin.diseases.index');
Route::get('/admin/diseases/create', [DiseaseController::class, 'create'])->name('admin.diseases.create');
Route::post('/admin/diseases', [DiseaseController::class, 'store'])->name('admin.diseases.store');

Route::get('/admin/diseases/{id}/edit', [DiseaseController::class, 'edit'])->name('admin.diseases.edit');
Route::put('/admin/diseases/{id}', [DiseaseController::class, 'update'])->name('admin.diseases.update');
Route::delete('/admin/diseases/{id}', [DiseaseController::class, 'destroyDashboard'])->name('admin.diseases.destroy');
#endregion

#region Treatment routes
Route::get('/admin/treatments', [TreatmentController::class, 'index'])->name('admin.treatments.index');
Route::get('/admin/treatments/create', [TreatmentController::class, 'create'])->name('admin.treatments.create');
Route::post('/admin/treatments', [TreatmentController::class, 'store'])->name('admin.treatments.store');

Route::get('/admin/treatments/{id}/edit', [TreatmentController::class, 'edit'])->name('admin.treatments.edit');
Route::put('/admin/treatments/{id}', [TreatmentController::class, 'updateDashboard'])->name('admin.treatments.update');
Route::delete('/admin/treatments/{id}', [TreatmentController::class, 'destroyDashboard'])->name('admin.treatments.destroy');
#endregion


});