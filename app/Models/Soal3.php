<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal3 extends Model
{
    use HasFactory;

    protected $table = 'soal3';
    protected $fillable = [
        'user_id', 'bahasa_inggris', 'bahasa_lain1', 'bahasa_lain2', 
        'bahasa_lain3', 'bahasa_lain4', 'nilai'
    ];
}
