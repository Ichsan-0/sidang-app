<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        // Tambah kolom baru ke tabel tugas_akhir
        Schema::table('tugas_akhir', function (Blueprint $table) {
            $table->string('judul')->after('mahasiswa_id');
            $table->text('latar_belakang')->nullable()->after('judul');
            $table->text('permasalahan')->nullable()->after('latar_belakang');
            $table->text('metode_penelitian')->nullable()->after('permasalahan');
        });
    }

    public function down(): void
    {
        // Hapus kolom yang baru ditambah
        Schema::table('tugas_akhir', function (Blueprint $table) {
            $table->dropColumn(['judul', 'latar_belakang', 'permasalahan', 'metode_penelitian']);
        });

        // (Opsional) Buat ulang tabel tugas_akhir_status jika perlu
        Schema::create('tugas_akhir_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tugas_akhir_id');
            $table->string('status');
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
    }
};