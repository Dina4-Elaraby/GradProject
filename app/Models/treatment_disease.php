<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class treatment_disease extends Model
{
    protected $fillable = [
        'disease_id',
        'treatment_id',
    ];

    public function disease()
    {
        return $this->belongsToMany(Disease::class);
    }

    public function treatment()
    {
        return $this->belongsToMany(Treatment::class);
    }
}
