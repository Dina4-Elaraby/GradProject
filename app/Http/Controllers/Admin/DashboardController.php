<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Disease;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Plant;
use App\Models\Treatment;

class DashboardController extends Controller
{
     public function index()
    {
        $users = User::all();
        $plants = Plant::all();
        $diseases = Disease::all();
        $treatments = Treatment::all();

        return view('admin.dashboard', compact('users', 'plants', 'diseases', 'treatments'));
    }
}
