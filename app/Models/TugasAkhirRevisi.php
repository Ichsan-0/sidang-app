<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasAkhirRevisi extends Model
{
    use HasFactory;

    protected $table = 'tugas_akhir_revisi';
    protected $fillable = [
        'tugas_akhir_id',
        'judul',
        'permasalahan',
        'metode_penelitian',
        'catatan',
        'deskripsi',
        'jenis_penelitian_id',
        'bidang_peminatan_id',
        'pembimbing_id'
    ];
    
    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }
}
