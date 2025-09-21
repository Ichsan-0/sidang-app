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
        Schema::create('ta_validasi_prodi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tugas_akhir_id');
            $table->unsignedBigInteger('user_id'); // admin prodi
            $table->unsignedBigInteger('pembimbing_id')->nullable();
            $table->tinyInteger('status'); // 1: diajukan, 2: disetujui, 3: ditolak
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('tugas_akhir_id')->references('id')->on('tugas_akhir')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('pembimbing_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ta_validasi_prodi');
    }
};
