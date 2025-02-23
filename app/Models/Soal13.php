<?php

// Model
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal13 extends Model
{
    use HasFactory;

    protected $table = 'soal13';

    protected $fillable = [
        'user_id',
        'guru_tetap',
        'asisten_guru',
        'owner_sekolah',
        'guru_tidak_tetap_offline',
        'guru_tidak_tetap_online',
        'guru_luar_negeri1',
        'guru_luar_negeri2',
        'nilai'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
