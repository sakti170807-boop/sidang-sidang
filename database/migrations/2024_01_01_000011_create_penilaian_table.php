<?php
// database/migrations/2024_01_01_000001_create_fakultas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_sidang_id')->constrained('jadwal_sidang')->cascadeOnDelete();
            $table->foreignId('penguji_id')->constrained('penguji');
            $table->decimal('nilai_presentasi', 5, 2)->nullable();
            $table->decimal('nilai_materi', 5, 2)->nullable();
            $table->decimal('nilai_diskusi', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penilaian');
    }
};
