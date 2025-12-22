<?php
// database/migrations/2024_01_01_000001_create_fakultas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penguji', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_sidang_id')->constrained('jadwal_sidang')->cascadeOnDelete();
            $table->foreignId('dosen_id')->constrained('users');
            $table->enum('posisi', ['ketua', 'sekretaris', 'penguji_1', 'penguji_2', 'penguji_3']);
            $table->enum('status_konfirmasi', ['pending', 'confirmed', 'declined'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penguji');
    }
};