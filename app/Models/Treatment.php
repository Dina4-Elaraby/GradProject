<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Treatment extends Model
{ 
    use HasFactory;
    protected $fillable = 
    [
        'description',
    ];  
    public function diseases()
    {
        //this treatment belong to many diseases,new table between them,contains two foreign keys
        //treatment_id and disease_id => orignal id,id
        return $this->belongsToMany(Disease::class, 'treatment_diseases', 'treatment_id', 'disease_id', 'id', 'id');
    }
}





