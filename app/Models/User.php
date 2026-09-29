<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'age',
        'reputation_score',
        'balance',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'age' => 'integer',
        'balance' => 'integer',
        'reputation_score' => 'integer',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'creator_id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class, 'helper_id');
    }

    public function isAdult()
    {
        return $this->age >= 14;
    }
}