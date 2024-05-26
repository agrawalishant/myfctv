<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Authenticatable
{
    use Notifiable;
    use HasFactory;
    protected $guard = 'admin';
    protected $fillable = [
        'name','type','mobile','email','password','created_at','updated_at',
    ];
    protected $hidden = [
        'password','remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function adminRoles()
    {
        return $this->hasMany(AdminRole::class);
    }
    
    
}