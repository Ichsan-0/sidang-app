<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankJudul extends Model
{
    use HasFactory;
    protected $table = 'bank_judul';
    protected $fillable = [
        'nama',
        'nim',
        'judul',
        'deskripsi',
        'prodi_id',
        'bidang_peminatan_id',
        'pembimbing_id',
        'pembimbing_2_id',
        'no_sk',
        'tgl_sk',
        'status',
        'created_by'
    ];

    // Relasi ke pembuat judul (user)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke program studi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    // Relasi ke bidang peminatan
    public function bidangPeminatan()
    {
        return $this->belongsTo(BidangPeminatan::class, 'bidang_peminatan_id');
    }

    // Relasi ke dosen pembimbing
    public function pembimbing()
    {
        return $this->belongsTo(User::class, 'pembimbing_id');
    }

    public function pembimbing2()
    {
        return $this->belongsTo(User::class, 'pembimbing_2_id');
    }
}
