<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements JWTSubject
{
    use  HasFactory, Notifiable;  
    protected $fillable = 
    [
        'name',
        'email',
        'password',
        'status',
    ];

    protected $hidden = 
    [
        'password',
        'remember_token',
    ];

    protected $casts = 
    [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'string',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}



   

    

