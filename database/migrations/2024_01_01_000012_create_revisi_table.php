<?php
// database/migrations/2024_01_01_000001_create_fakultas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('revisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_sidang_id')->constrained('jadwal_sidang')->cascadeOnDelete();
            $table->foreignId('penguji_id')->constrained('penguji');
            $table->text('catatan_revisi');
            $table->enum('status', ['pending', 'submitted', 'approved'])->default('pending');
            $table->string('file_revisi')->nullable();
            $table->timestamp('tanggal_submit')->nullable();
            $table->timestamp('tanggal_approve')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('revisi');
    }
};
