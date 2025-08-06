<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPenelitian extends Model
{
    use HasFactory;
    protected $table = 'jenis_penelitians';
    protected $fillable = [
        'kode',
        'nama',
        'ket',
    ];
}
