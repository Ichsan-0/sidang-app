<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Prodi;

class Fakultas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kode',
        'ket',
    ];

    public function prodis()
    {
        return $this->hasMany(Prodi::class);
    }
}
