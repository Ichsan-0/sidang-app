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
        Schema::create('pin_ijazah_transkrip', function (Blueprint $table) {
            $table->id();
            $table->string('pin_ijazah')->nullable();
            $table->string('nomor_transkrip')->nullable();
            $table->unsignedBigInteger('nim')->nullable();
            $table->string('nama')->nullable();
            $table->string('judul')->nullable();
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->unsignedBigInteger('nik')->nullable();
            $table->enum('status_awal', ['1', '2'])->nullable();
            $table->date('tanggal_lulus')->nullable();
            $table->date('tanggal_transkrip')->nullable();
            $table->date('tanggal_ijazah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
