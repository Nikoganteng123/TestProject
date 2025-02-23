<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal8 extends Model
{
    use HasFactory;

    protected $table = "soal8";
    protected $fillable = [
        'user_id',
        'demo_dpp_dpd1',
        'demo_dpp_dpd2',
        'demo_dpp_dpd3',
        'demo_dpp_dpd4',
        'demo_dpp_dpd5',
        'non_ipbi1',
        'non_ipbi2',
        'non_ipbi3',
        'non_ipbi4',
        'non_ipbi5',
        'international1',
        'international2',
        'nilai'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}