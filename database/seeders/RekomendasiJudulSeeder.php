<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RekomendasiJudul;
use App\Models\User;
use App\Models\BidangPeminatan;

class RekomendasiJudulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data referensi
        $dosen1 = User::role('dosen')->first();
        $dosen2 = User::role('dosen')->skip(1)->first();
        $bidangPeminatan1 = BidangPeminatan::first();
        $bidangPeminatan2 = BidangPeminatan::skip(1)->first();

        $data = [
            [
                'id_dosen' => $dosen1->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan1->id ?? 1,
                'topik' => 'Keamanan Sistem',
                'judul' => 'Implementasi Blockchain untuk Sistem Voting Digital',
                'format_penelitian' => 'Studi Kasus',
                'jenis_publikasi' => 'Jurnal Nasional',
            ],
            [
                'id_dosen' => $dosen1->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan1->id ?? 1,
                'topik' => 'Machine Learning',
                'judul' => 'Analisis Sentimen Media Sosial menggunakan Deep Learning',
                'format_penelitian' => 'Eksperimen',
                'jenis_publikasi' => 'Jurnal Internasional',
            ],
            [
                'id_dosen' => $dosen2->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan2->id ?? 1,
                'topik' => 'Sistem Rekomendasi',
                'judul' => 'Sistem Rekomendasi Produk E-Commerce dengan Collaborative Filtering',
                'format_penelitian' => 'Pengembangan Sistem',
                'jenis_publikasi' => 'Konferensi Nasional',
            ],
            [
                'id_dosen' => $dosen1->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan1->id ?? 1,
                'topik' => 'Augmented Reality',
                'judul' => 'Augmented Reality untuk Panduan Wisata Interaktif',
                'format_penelitian' => 'Pengembangan Aplikasi',
                'jenis_publikasi' => 'Jurnal Nasional Terakreditasi',
            ],
            [
                'id_dosen' => $dosen2->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan2->id ?? 1,
                'topik' => 'Internet of Things',
                'judul' => 'Internet of Things untuk Smart Home Automation',
                'format_penelitian' => 'Prototype',
                'jenis_publikasi' => 'Konferensi Internasional',
            ],
            [
                'id_dosen' => $dosen1->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan1->id ?? 1,
                'topik' => 'Computer Vision',
                'judul' => 'Computer Vision untuk Deteksi Objek Real-Time',
                'format_penelitian' => 'Eksperimen',
                'jenis_publikasi' => 'Jurnal Internasional Q2',
            ],
            [
                'id_dosen' => $dosen2->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan2->id ?? 1,
                'topik' => 'Business Intelligence',
                'judul' => 'Sistem Informasi Keuangan dengan Business Intelligence Dashboard',
                'format_penelitian' => 'Studi Kasus',
                'jenis_publikasi' => 'Jurnal Nasional',
            ],
            [
                'id_dosen' => $dosen1->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan1->id ?? 1,
                'topik' => 'Algoritma Optimasi',
                'judul' => 'Optimasi Algoritma Genetika untuk Penjadwalan Kuliah',
                'format_penelitian' => 'Komparasi Algoritma',
                'jenis_publikasi' => 'Konferensi Nasional',
            ],
            [
                'id_dosen' => $dosen2->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan2->id ?? 1,
                'topik' => 'Web Development',
                'judul' => 'Progressive Web App untuk Aplikasi Kesehatan',
                'format_penelitian' => 'Pengembangan Aplikasi',
                'jenis_publikasi' => 'Jurnal Nasional Terakreditasi',
            ],
            [
                'id_dosen' => $dosen1->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan1->id ?? 1,
                'topik' => 'Face Recognition',
                'judul' => 'Sistem Pengenalan Wajah dengan Face Recognition API',
                'format_penelitian' => 'Prototype',
                'jenis_publikasi' => 'Konferensi Internasional',
            ],
            [
                'id_dosen' => $dosen2->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan2->id ?? 1,
                'topik' => 'Natural Language Processing',
                'judul' => 'Chatbot Customer Service dengan Natural Language Processing',
                'format_penelitian' => 'Pengembangan Sistem',
                'jenis_publikasi' => 'Jurnal Internasional Q3',
            ],
            [
                'id_dosen' => $dosen1->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan1->id ?? 1,
                'topik' => 'Cloud Computing',
                'judul' => 'Implementasi Microservices Architecture pada Cloud Platform',
                'format_penelitian' => 'Studi Kasus',
                'jenis_publikasi' => 'Jurnal Nasional',
            ],
            [
                'id_dosen' => $dosen2->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan2->id ?? 1,
                'topik' => 'Cybersecurity',
                'judul' => 'Sistem Keamanan Jaringan dengan Intrusion Detection System',
                'format_penelitian' => 'Eksperimen',
                'jenis_publikasi' => 'Konferensi Nasional',
            ],
            [
                'id_dosen' => $dosen1->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan1->id ?? 1,
                'topik' => 'Mobile Development',
                'judul' => 'Aplikasi Mobile Monitoring Kesehatan dengan Flutter',
                'format_penelitian' => 'Pengembangan Aplikasi',
                'jenis_publikasi' => 'Jurnal Nasional Terakreditasi',
            ],
            [
                'id_dosen' => $dosen2->id ?? 1,
                'bidang_peminatan_id' => $bidangPeminatan2->id ?? 1,
                'topik' => 'Data Mining',
                'judul' => 'Prediksi Churn Customer menggunakan Data Mining',
                'format_penelitian' => 'Komparasi Algoritma',
                'jenis_publikasi' => 'Jurnal Internasional',
            ],
        ];

        foreach ($data as $item) {
            RekomendasiJudul::create($item);
        }

        $this->command->info('✅ Rekomendasi Judul seeder berhasil dijalankan! (15 data)');
    }
}
