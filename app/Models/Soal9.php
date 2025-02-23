<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal9 extends Model
{
    use HasFactory;

    protected $table = "soal9";
    protected $fillable = [
        'user_id',
        'pembina_demonstrator',
        'panitia',
        'peserta',
        'nilai'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
