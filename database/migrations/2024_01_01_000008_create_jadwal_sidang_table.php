<?php
// database/migrations/2024_01_01_000001_create_fakultas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



return new class extends Migration
{
    public function up()
    {
        Schema::create('jadwal_sidang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruang_sidang_id')->constrained('ruang_sidang')->cascadeOnDelete();
            $table->foreignId('kategori_sidang_id')->constrained('kategori_sidang')->cascadeOnDelete();
            $table->foreignId('pembimbing_id')->constrained('pembimbing')->cascadeOnDelete();
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->string('status')->default('terjadwal');
            $table->text('catatan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal_sidang');
    }
};