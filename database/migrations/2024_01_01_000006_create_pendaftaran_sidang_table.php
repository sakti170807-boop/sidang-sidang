<?php
// database/migrations/2024_01_01_000001_create_fakultas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pendaftaran_sidang', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pendaftaran')->unique();
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('kategori_sidang_id')->constrained('kategori_sidang');
            $table->foreignId('program_studi_id')->constrained('program_studi');
            $table->string('judul');
            $table->text('abstrak')->nullable();
            $table->enum('status', ['draft', 'submitted', 'verified_pembimbing', 'verified_admin', 'scheduled', 'completed', 'rejected'])->default('draft');
            $table->text('catatan_admin')->nullable();
            $table->timestamp('tanggal_submit')->nullable();
            $table->timestamp('tanggal_verifikasi_pembimbing')->nullable();
            $table->timestamp('tanggal_verifikasi_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pendaftaran_sidang');
    }
};
