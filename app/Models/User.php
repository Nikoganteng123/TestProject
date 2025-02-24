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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture',
        'nilai',
        'last_submission_date',
        'can_take_test',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_submission_date' => 'datetime',
        'can_take_test' => 'boolean',
    ];

    /**
     * Cek apakah user bisa mengikuti uji kompetensi lagi
     *
     * @return bool
     */
    public function canTakeTest()
    {
        if (!$this->last_submission_date) {
            return true;
        }

        $nextAvailableDate = Carbon::parse($this->last_submission_date)->addMonths(3);
        return Carbon::now()->greaterThanOrEqualTo($nextAvailableDate);
    }

    /**
     * Hitung hari tersisa hingga bisa mengikuti uji kompetensi lagi
     *
     * @return int
     */
    public function remainingDays()
    {
        if (!$this->last_submission_date) {
            return 0;
        }

        $nextAvailableDate = Carbon::parse($this->last_submission_date)->addMonths(3);
        return Carbon::now()->lessThan($nextAvailableDate) 
            ? Carbon::now()->diffInDays($nextAvailableDate) 
            : 0;
    }

    /**
     * Dapatkan tanggal tersedia berikutnya
     *
     * @return string|null
     */
    public function nextAvailableDate()
    {
        if (!$this->last_submission_date) {
            return null;
        }

        return Carbon::parse($this->last_submission_date)->addMonths(3)->toDateTimeString();
    }

    /**
     * Aksesor untuk profile_picture agar mendapatkan URL lengkap
     *
     * @return string|null
     */
    public function getProfilePictureAttribute($value)
    {
        if ($value) {
            return asset('storage/profile_pictures/' . $value);
        }
        return null;
    }
}