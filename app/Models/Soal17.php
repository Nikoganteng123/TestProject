<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal17 extends Model
{
    use HasFactory;

    protected $table = 'soal17';

    protected $fillable = [
        'user_id',
        'media_cetak_nasional',
        'media_cetak_internasional',
        'buku_merangkai_bunga',
        'kontributor_buku1',
        'kontributor_buku2',
        'kontributor_tv1',
        'kontributor_tv2',
        'nilai'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}