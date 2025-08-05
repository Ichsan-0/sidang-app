<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prodi;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prodis = [
            [
                'nama_prodi' => 'Pendidikan Agama Islam',
                'kode_prodi' => 'PAI',
                'id_fakultas' => 1,
                'ket' => 'Program Studi Pendidikan Agama Islam',
            ],
            [
                'nama_prodi' => 'Pendidikan Bahasa Arab',
                'kode_prodi' => 'PBA',
                'id_fakultas' => 1,
                'ket' => 'Program Studi Pendidikan Bahasa Arab',
            ],
            [
                'nama_prodi' => 'Pendidikan Bahasa Inggris',
                'kode_prodi' => 'PBI',
                'id_fakultas' => 1,
                'ket' => 'Program Studi Pendidikan Bahasa Inggris',
            ],
            [
                'nama_prodi' => 'Manajemen Pendidikan Islam',
                'kode_prodi' => 'MPI',
                'id_fakultas' => 1,
                'ket' => 'Program Studi Manajemen Pendidikan Islam',
            ],
            [
                'nama_prodi' => 'Hukum Keluarga',
                'kode_prodi' => 'HK',
                'id_fakultas' => 2,
                'ket' => 'Program Studi Hukum Keluarga',
            ],
            [
                'nama_prodi' => 'Perbandingan Mazhab',
                'kode_prodi' => 'PM',
                'id_fakultas' => 2,
                'ket' => 'Program Studi Perbandingan Mazhab',
            ]
        ];

        foreach ($prodis as $data) {
            Prodi::create($data);
        }
    }
}
