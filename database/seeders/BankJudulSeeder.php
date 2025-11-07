<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BankJudul;
use App\Models\User;
use App\Models\Prodi;
use App\Models\BidangPeminatan;
use Illuminate\Support\Facades\DB;

class BankJudulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data referensi
        $adminProdi = User::role('admin prodi')->first();
        $dosen1 = User::role('dosen')->first();
        $dosen2 = User::role('dosen')->skip(1)->first();
        $prodi = Prodi::first();
        $bidangPeminatan = BidangPeminatan::first();

        $data = [
            [
                'nama' => 'Ahmad Fauzi',
                'nim' => 11210001,
                'judul' => 'Sistem Informasi Manajemen Perpustakaan Berbasis Web',
                'deskripsi' => 'Aplikasi untuk mengelola data perpustakaan meliputi peminjaman, pengembalian, dan inventori buku',
                'prodi_id' => $prodi->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan->id ?? 1,
                'pembimbing_id' => $dosen1->id ?? null,
                'pembimbing_2_id' => $dosen2->id ?? null,
                'no_sk' => 123001,
                'tgl_sk' => '2024-01-15',
                'status' => 1, // Aktif
                'created_by' => $adminProdi->id ?? 1,
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'nim' => 11210002,
                'judul' => 'Aplikasi E-Learning dengan Fitur Video Conference',
                'deskripsi' => 'Platform pembelajaran online dengan integrasi video conference dan manajemen kelas',
                'prodi_id' => $prodi->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan->id ?? 1,
                'pembimbing_id' => $dosen1->id ?? null,
                'pembimbing_2_id' => null,
                'no_sk' => 123002,
                'tgl_sk' => '2024-01-20',
                'status' => 1, // Aktif
                'created_by' => $adminProdi->id ?? 1,
            ],
            [
                'nama' => 'Budi Santoso',
                'nim' => 11210003,
                'judul' => 'Sistem Pakar Diagnosa Penyakit Tanaman dengan Metode Forward Chaining',
                'deskripsi' => 'Aplikasi berbasis kecerdasan buatan untuk mendiagnosa penyakit pada tanaman pertanian',
                'prodi_id' => $prodi->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan->id ?? 1,
                'pembimbing_id' => $dosen2->id ?? null,
                'pembimbing_2_id' => $dosen1->id ?? null,
                'no_sk' => 123003,
                'tgl_sk' => '2024-02-01',
                'status' => 2, // Selesai
                'created_by' => $adminProdi->id ?? 1,
            ],
            [
                'nama' => 'Dewi Lestari',
                'nim' => 11210004,
                'judul' => 'Aplikasi Mobile Monitoring Kesehatan Ibu Hamil',
                'deskripsi' => 'Aplikasi Android untuk monitoring kesehatan ibu hamil dengan notifikasi jadwal kontrol',
                'prodi_id' => $prodi->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan->id ?? 1,
                'pembimbing_id' => $dosen1->id ?? null,
                'pembimbing_2_id' => null,
                'no_sk' => 123004,
                'tgl_sk' => '2024-02-10',
                'status' => 1, // Aktif
                'created_by' => $adminProdi->id ?? 1,
            ],
            [
                'nama' => 'Rizki Pratama',
                'nim' => 11210005,
                'judul' => 'Sistem Informasi Geografis Pemetaan Lokasi Wisata',
                'deskripsi' => 'Aplikasi GIS untuk pemetaan dan informasi lokasi wisata berbasis web',
                'prodi_id' => $prodi->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan->id ?? 1,
                'pembimbing_id' => $dosen2->id ?? null,
                'pembimbing_2_id' => null,
                'no_sk' => 123005,
                'tgl_sk' => '2024-02-15',
                'status' => 2, // Selesai
                'created_by' => $adminProdi->id ?? 1,
            ],
            [
                'nama' => 'Aisyah Ramadhan',
                'nim' => 11210006,
                'judul' => 'Chatbot Customer Service dengan Natural Language Processing',
                'deskripsi' => 'Implementasi chatbot untuk layanan pelanggan menggunakan NLP dan machine learning',
                'prodi_id' => $prodi->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan->id ?? 1,
                'pembimbing_id' => $dosen1->id ?? null,
                'pembimbing_2_id' => $dosen2->id ?? null,
                'no_sk' => 123006,
                'tgl_sk' => '2024-03-01',
                'status' => 1, // Aktif
                'created_by' => $adminProdi->id ?? 1,
            ],
            [
                'nama' => 'Muhammad Iqbal',
                'nim' => 11210007,
                'judul' => 'Sistem Keamanan Jaringan dengan Intrusion Detection System',
                'deskripsi' => 'Implementasi IDS untuk deteksi dan pencegahan serangan pada jaringan komputer',
                'prodi_id' => $prodi->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan->id ?? 1,
                'pembimbing_id' => $dosen2->id ?? null,
                'pembimbing_2_id' => null,
                'no_sk' => 123007,
                'tgl_sk' => '2024-03-10',
                'status' => 1, // Aktif
                'created_by' => $adminProdi->id ?? 1,
            ],
        ];

        foreach ($data as $item) {
            BankJudul::create($item);
        }

        $this->command->info('Bank Judul seeder berhasil dijalankan!');
    }
}
