<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekomendasiJudul extends Model
{
    use HasFactory;
    protected $table = 'rekomendasi_judul';
    protected $fillable = [
        'id_dosen', 'bidang_peminatan_id', 'topik', 'judul', 'format_penelitian', 'jenis_publikasi', 'status', 'created_at', 'updated_at'
    ];
    public function dosen()
    {
        return $this->belongsTo(User::class, 'id_dosen');
    }
    public function bidangPeminatan()
    {
        return $this->belongsTo(BidangPeminatan::class, 'bidang_peminatan_id');
    }
    

}
   
