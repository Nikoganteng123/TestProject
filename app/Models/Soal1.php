<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal1 extends Model
{
    use HasFactory;

    protected $table = 'soal1';  // Nama tabel di database

    protected $fillable = [
        'user_id', 'tingkat_pendidikan', 'nilai'
    ];

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
