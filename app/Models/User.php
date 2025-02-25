<?php

// app/Models/User.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'last_submission_date',
        'can_take_test',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_submission_date' => 'datetime',
        'can_take_test' => 'boolean',
    ];

    // Ubah dari 3 bulan menjadi 30 detik
    public function getNextAvailableDateAttribute()
    {
        return $this->last_submission_date 
            ? Carbon::parse($this->last_submission_date)->addSeconds(30)
            : null;
    }
}