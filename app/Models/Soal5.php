<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal5 extends Model
{
    use HasFactory;

    protected $table = 'soal5';

    protected $fillable = [
        'user_id',
        'sertifikat_1',
        'sertifikat_2',
        'sertifikat_3',
        'nilai'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
