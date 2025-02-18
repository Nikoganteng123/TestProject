<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal2 extends Model
{
    use HasFactory;

    protected $table = 'soal2';
    protected $fillable = [
        'user_id',
        'tp3',
        'lpmp_diknas',
        'guru_lain_ipbi_1',
        'guru_lain_ipbi_2',
        'guru_lain_ipbi_3',
        'guru_lain_ipbi_4',
        'training_trainer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

