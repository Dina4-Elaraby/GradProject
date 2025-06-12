<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Image;
class Plant extends Model
{ 
    use HasFactory;
    protected $fillable = 
    [
        'scientific_name',
        'common_name',
        'plant_family',
        'care_instructions',
        'image',
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function diseases()
    {
        //this plant belong to many diseases,new table between them,contains two foreign keys
        //plant_id and disease_id => orignal id,id
        return $this->belongsToMany(Disease::class, 'plant_diseases', 'plant_id', 'disease_id', 'id', 'id');
    }
    
}
