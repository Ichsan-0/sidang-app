<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkProposalTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sk_proposal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tugas_akhir_id');
            $table->string('judul_revisi');
            $table->unsignedBigInteger('pembimbing_id');
            $table->date('tanggal_sk')->nullable();
            $table->date('tanggal_expired')->nullable();
            $table->string('file_sk')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();

            $table->foreign('tugas_akhir_id')->references('id')->on('tugas_akhir')->onDelete('cascade');
            $table->foreign('pembimbing_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sk_proposal');
    }
};
