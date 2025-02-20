<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal4 extends Model
{
    use HasFactory;

    // Secara eksplisit mendefinisikan nama tabel
    protected $table = 'soal4';

    protected $fillable = [
        'user_id',
        'nilai',
        'independent_org',
        'foreign_school_degree',
        'foreign_school_no_degree_1',
        'foreign_school_no_degree_2',
        'foreign_school_no_degree_3',
        'foreign_school_no_degree_4',
        'foreign_school_no_degree_5',
        'domestic_school_no_degree_1',
        'domestic_school_no_degree_2',
        'domestic_school_no_degree_3',
        'domestic_school_no_degree_4',
        'domestic_school_no_degree_5',
    ];
}
