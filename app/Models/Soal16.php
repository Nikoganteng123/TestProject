<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal16 extends Model
{
    use HasFactory;

    protected $table = 'soal16';

    protected $fillable = [
        'user_id',
        'aktif_merangkai',
        'owner_berbadan_hukum',
        'owner_tanpa_badan_hukum',
        'freelance_designer',
        'nilai'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}