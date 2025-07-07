<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun',
        'ket',
        'periode_awal',
        'periode_akhir',
        'is_aktif',
    ];
}
