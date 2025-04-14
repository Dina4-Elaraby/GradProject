<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    protected $fillable = [
        'DHT_ReadingDate',
        'DHT_ReadingTime',
        'DHT_Temperature_C',
        'DHT_Temperature_F',
        'DHT_Humidity',
        'image_base64',
        'is_moist',
        'WaterLevelStatus',
    ];
}
