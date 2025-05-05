<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use HasFactory;
class Device extends Model
{
    

    protected $fillable = ['user_id', 'name', 'mac_address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function measurements()
    {
        return $this->hasMany(Measurement::class);
    }



}