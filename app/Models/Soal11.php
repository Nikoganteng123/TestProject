<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal11 extends Model
{
    use HasFactory;

    protected $table = 'soal11';

    protected $fillable = [
        'user_id',
        'penguji_sertifikasi1', 'penguji_sertifikasi2',
        'juri_ipbi1', 'juri_ipbi2',
        'juri_non_ipbi1', 'juri_non_ipbi2',
        'nilai'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}