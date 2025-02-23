<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal15 extends Model
{
    use HasFactory;

    protected $table = 'soal15';

    protected $fillable = [
        'user_id',
        'ikebana_murid',
        'ikebana_guru',
        'rangkaian_tradisional',
        'lainnya',
        'nilai'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}