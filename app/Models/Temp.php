<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Temp extends Model
{
    use HasFactory;
    protected $table = 'temp'; 
    protected $fillable = 
    [
        'image_path','plant_id'
    ];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }
}
