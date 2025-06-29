<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'image_path',
        'plant_type',
        'diagnosis',
        'plant_id',
        'second_prediction',
        'third_prediction',
    ];
    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }
    
}
