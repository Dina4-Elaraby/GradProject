<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use HasFactory;
class Measurement extends Model
{
    
    protected $fillable = ['device_id', 'water_level', 'dht_humidity', 'dht_temperature', 'is_moist'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
    
}
