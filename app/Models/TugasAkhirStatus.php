<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasAkhirStatus extends Model
{
    use HasFactory;
    
    protected $table = 'tugas_akhir_status';
    protected $fillable = [
        'id', 'tugas_akhir_id', 'status', 'catatan','user_id'
    ];

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }
}
