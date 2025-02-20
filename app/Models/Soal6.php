<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal6 extends Model
{
    protected $table = "soal6";
    protected $fillable = [
        'user_id',
        'penghargaan_daerah',
        'penghargaan_nasional',
        'penghargaan_internasional',
        'nilai'
    ];
}
