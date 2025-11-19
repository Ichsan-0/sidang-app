<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PinIjazahTranskripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pin_ijazah_transkrip')->insert([
            [
                'pin_ijazah' => 'PIN123456',
                'nomor_transkrip' => 'TRX2025001',
                'nim' => 11210001,
                'nama' => 'Ahmad Fauzi',
                'judul' => 'Sistem Informasi Akademik',
                'prodi_id' => 1,
                'nik' => 3201010101010001,
                'status_awal' => '1',
                'tanggal_lulus' => '2025-07-01',
                'tanggal_transkrip' => '2025-07-10',
                'tanggal_ijazah' => '2025-07-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pin_ijazah' => 'PIN654321',
                'nomor_transkrip' => 'TRX2025002',
                'nim' => 11210002,
                'nama' => 'Siti Nurhaliza',
                'judul' => 'Aplikasi E-Learning',
                'prodi_id' => 2,
                'nik' => 3201010101010002,
                'status_awal' => '2',
                'tanggal_lulus' => '2025-06-20',
                'tanggal_transkrip' => '2025-06-25',
                'tanggal_ijazah' => '2025-07-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pin_ijazah' => 'PIN789012',
                'nomor_transkrip' => 'TRX2025003',
                'nim' => 11210003,
                'nama' => 'Budi Santoso',
                'judul' => 'Sistem Pakar Diagnosa Tanaman',
                'prodi_id' => 1,
                'nik' => 3201010101010003,
                'status_awal' => '1',
                'tanggal_lulus' => '2025-05-15',
                'tanggal_transkrip' => '2025-05-20',
                'tanggal_ijazah' => '2025-05-25',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
