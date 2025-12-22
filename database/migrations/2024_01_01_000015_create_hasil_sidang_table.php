<?php
// database/migrations/2024_01_01_000001_create_fakultas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hasil_sidang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_sidang_id')->constrained('jadwal_sidang')->cascadeOnDelete();
            $table->enum('keputusan', ['lulus', 'lulus_bersyarat', 'tidak_lulus', 'pending'])->default('pending');
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->string('grade', 2)->nullable();
            $table->text('catatan')->nullable();
            $table->string('nomor_berita_acara')->nullable();
            $table->string('file_berita_acara')->nullable();
            $table->timestamp('tanggal_keputusan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hasil_sidang');
    }
};