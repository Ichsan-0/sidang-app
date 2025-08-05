<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fakultas;

class FakultasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fakultas = [
            [
                'nama' => 'Fakultas Syariah dan Hukum',
                'kode' => 'FSH',
                'ket'  => 'Fakultas Syariah dan Hukum'
            ],
            [
                'nama' => 'Fakultas Dakwah dan Komunikasi',
                'kode' => 'FDK',
                'ket'  => 'Fakultas Dakwah dan Komunikasi'
            ],
            [
                'nama' => 'Fakultas Ushuluddin dan Filsafat',
                'kode' => 'FUF',
                'ket'  => 'Fakultas Ushuluddin dan Filsafat'
            ],
            [
                'nama' => 'Fakultas Adab dan Humaniora',
                'kode' => 'FAH',
                'ket'  => 'Fakultas Adab dan Humaniora'
            ],
            [
                'nama' => 'Fakultas Ekonomi dan Bisnis Islam',
                'kode' => 'FEBI',
                'ket'  => 'Fakultas Ekonomi dan Bisnis Islam'
            ],
            [
                'nama' => 'Fakultas Psikologi',
                'kode' => 'FPSI',
                'ket'  => 'Fakultas Psikologi'
            ],
            [
                'nama' => 'Fakultas Sains dan Teknologi',
                'kode' => 'FST',
                'ket'  => 'Fakultas Sains dan Teknologi'
            ],
            [
                'nama' => 'Fakultas Ilmu Sosial dan Ilmu Pemerintahan',
                'kode' => 'FISIP',
                'ket'  => 'Fakultas Ilmu Sosial dan Ilmu Pemerintahan'
            ],
            [
                'nama' => 'Pascasarjana',
                'kode' => 'PSG',
                'ket'  => 'Pascasarjana'
            ]
        ];

        foreach ($fakultas as $data) {
            Fakultas::create($data);
        }
    }
}
