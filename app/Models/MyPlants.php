<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyPlants extends Model
{
    protected $table = 'my_plants';

    protected $fillable = [
        'user_id',
        'plant_id',
        'image_id',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
