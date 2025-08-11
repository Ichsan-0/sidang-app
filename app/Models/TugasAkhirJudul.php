<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasAkhirJudul extends Model
{
    use HasFactory;

    protected $table = 'tugas_akhir_judul';
    protected $fillable = ['tugas_akhir_id', 'judul'];

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }
}
