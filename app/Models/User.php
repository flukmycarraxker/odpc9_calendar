<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];



    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Helper function เช็คสิทธิ์
    public function isAdmin()
    {
    return $this->role_id == 1;
    }

    public function isUser()
    {
    return $this->role_id == 2;
    }   

    public function isGuest()
    {
    return $this->role_id == 3;
    }
}
