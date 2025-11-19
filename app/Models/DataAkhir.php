<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataAkhir extends Model
{
    use HasFactory;
    protected $table = 'pin_ijazah_transkrip';
    protected $fillable = [
        'pin_ijazah',
        'nomor_seri_ijazah',
        'nomor_transkrip',
        'nim',
        'nama',
        'judul',
        'prodi_id',
        'nik',
        'status_awal',
        'tanggal_lulus',
        'tanggal_transkrip',
        'tanggal_ijazah',
    ];

    
}
