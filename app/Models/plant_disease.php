<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class plant_disease extends Model
{
    protected $table = 'plant_diseases';

    protected $fillable = [
        'plant_id',
        'disease_id',
    ];

    public function plant()
    {
        return $this->belongsToMany(Plant::class, 'plant_id');
    }

    public function disease()
    {
        return $this->belongsToMany(Disease::class, 'disease_id');
    }
}
