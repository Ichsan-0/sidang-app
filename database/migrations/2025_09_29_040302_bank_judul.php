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
        Schema::create('bank_judul', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('nim');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->unsignedBigInteger('bidang_peminatan_id')->nullable();
            $table->unsignedBigInteger('pembimbing_id')->nullable();
            $table->unsignedBigInteger('pembimbing_2_id')->nullable();
            $table->integer('no_sk')->nullable();
            $table->date('tgl_sk')->nullable();
            $table->enum('status', ['1', '2'])->default('1'); // 1: aktif, 2: selesai
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_judul');
    }
};
