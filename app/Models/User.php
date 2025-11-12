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
        'name', 'email', 'password', 'is_admin', 'profile_picture', 'nilai', 'temporary_score',
        'last_submission_date', 'is_verified', 'can_take_test', 'status', 'pekerjaan',
        'tanggal_lahir', 'informasi_ipbi', 'domisili','NoHP'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_verified' => 'boolean',
        'can_take_test' => 'boolean',
    ];

    protected $dates = [
        'tanggal_lahir', 'last_submission_date'
    ];

    // Ubah dari 3 bulan menjadi 30 detik
    public function getNextAvailableDateAttribute()
    {
        return $this->last_submission_date 
            ? Carbon::parse($this->last_submission_date)->addMonths(3)
            : null;
    }
}