<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal7 extends Model
{
    use HasFactory;

    protected $table = "soal7";
    protected $fillable = [
        'user_id',
        'juara_nasional_dpp',
        'juara_non_dpp',
        'juara_instansi_lain',
        'juara_internasional',
        'peserta_lomba_1',
        'peserta_lomba_2',
        'peserta_lomba_3',
        'peserta_lomba_4',
        'peserta_lomba_5',
        'juri_lomba_1',
        'juri_lomba_2',
        'nilai'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}