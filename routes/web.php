<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');

Route::get('/db-check', function () {
    try {
        DB::connection()->getPdo();
        return "✅ Connected successfully to DB!";
    } catch (\Exception $e) {
        return "❌ DB Connection error: " . $e->getMessage();
    }
});









    
});
