<?php

// Model
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal14 extends Model
{
    use HasFactory;

    protected $table = 'soal14';

    protected $fillable = [
        'user_id',
        'ngajar_online',
        'nilai'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}