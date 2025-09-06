<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TugasAkhirStatus;
use App\Models\JenisPenelitian;
use App\Models\BidangPeminatan;
use App\Models\User;

class TugasAkhir extends Model
{
    use HasFactory;

    protected $table = 'tugas_akhir';
    protected $fillable = [
        'mahasiswa_id',
        'judul',
        'latar_belakang',
        'permasalahan',
        'metode_penelitian',
        'deskripsi',
        'jenis_penelitian_id',
        'bidang_peminatan_id',
        'pembimbing_id',
        'file'
    ];

    public function status()
    {
        return $this->hasMany(TugasAkhirStatus::class, 'tugas_akhir_id');
    }

    public function jenisPenelitian()
    {
        return $this->belongsTo(JenisPenelitian::class, 'jenis_penelitian_id');
    }

    public function bidangPeminatan()
    {
        return $this->belongsTo(BidangPeminatan::class, 'bidang_peminatan_id');
    }
    public function pembimbing()
    {
        return $this->belongsTo(User::class, 'pembimbing_id');
    }
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}
