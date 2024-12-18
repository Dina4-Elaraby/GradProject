<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dht22Sensor extends Model
{  use HasFactory;
    protected $table = 'dht22_sensor';

  
    protected $fillable = [
        'temperature',
        'humidity',
        'reading_date',
        'reading_time',
    ];
    
}
