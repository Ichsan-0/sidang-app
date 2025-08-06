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
        Schema::create('bidang_peminatan', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('jenis_penelitian_id')->constrained('jenis_penelitian')->onDelete('cascade');
            $table->foreignId('id_prodi')->constrained('prodi')->onDelete('cascade');
            $table->string('kode', 50);
            $table->string('nama', 255);
            $table->text('ket')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bidang_peminatan');
    }
};
