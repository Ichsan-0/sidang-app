<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ta_validasi_prodi extends Model
{
    use HasFactory;
    protected $table = 'ta_validasi_prodi';
    protected $fillable = [
        'tugas_akhir_id',
        'kode_sk',
        'user_id',
        'pembimbing_id',
        'status',
        'catatan',
        'created_at',
        'updated_at'
    ];
    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
