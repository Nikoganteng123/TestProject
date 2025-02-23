<?php
// Model
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal10 extends Model
{
    use HasFactory;

    protected $table = 'soal10';

    protected $fillable = [
        'user_id',
        'ipbi_offline1', 'ipbi_offline2', 'ipbi_offline3',
        'ipbi_online1', 'ipbi_online2', 'ipbi_online3',
        'non_ipbi_offline1', 'non_ipbi_offline2', 'non_ipbi_offline3',
        'non_ipbi_online1', 'non_ipbi_online2', 'non_ipbi_online3',
        'international_offline1', 'international_offline2',
        'international_online1', 'international_online2',
        'host_moderator1', 'host_moderator2', 'host_moderator3', 
        'host_moderator4', 'host_moderator5',
        'nilai'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
