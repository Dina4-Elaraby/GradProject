<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    use HasFactory;
    protected $fillable =
     [
        'name', 
        'symptoms', 
        'factors', 
        'image',
    ];

    //define attributes are arrays
    //laravel convert from JSON strings to PHP arrays when you retrieve them from the database.
    protected $casts = [
        'symptoms' => 'array',
        'factors' => 'array',
    ];

    public function plants()
    {
        //this disease belong to many plants,new table between them,contains two foreign keys
        //plant_id and disease_id => orignal id,id
        return $this->belongsToMany(Plant::class, 'plant_diseases', 'disease_id', 'plant_id', 'id', 'id');
    }
}





