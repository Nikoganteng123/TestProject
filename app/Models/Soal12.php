<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal12 extends Model
{
    use HasFactory;

    protected $table = 'soal12';

    protected $fillable = [
        'user_id',
        'jabatan',
        'nilai'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}