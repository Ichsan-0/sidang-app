<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankJudul extends Model
{
    use HasFactory;
    protected $table = 'bank_judul';
    protected $fillable = [
        'judul', 'deskripsi', 'prodi_id', 'bidang_peminatan_id', 'pembimbing_id', 'status', 'created_by'
    ];
}
