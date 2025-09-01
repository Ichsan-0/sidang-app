<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tugas_akhir', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->foreignId('jenis_penelitian_id')->constrained()->onDelete('cascade');
            $table->foreignId('bidang_peminatan_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('pembimbing_id')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
        });
        Schema::create('tugas_akhir_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tugas_akhir_id');
            $table->string('status'); // diajukan, revisi, disetujui, ditolak, dll
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('user_id'); // siapa yang mengubah status
            $table->timestamps();
        });

        Schema::create('tugas_akhir_judul', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tugas_akhir_id');
            $table->string('judul');
            $table->timestamps();

            $table->foreign('tugas_akhir_id')->references('id')->on('tugas_akhir')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_akhir');
        Schema::dropIfExists('tugas_akhir_status');
        Schema::dropIfExists('tugas_akhir_judul');
    }
};
