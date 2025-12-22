<?php
// database/migrations/2024_01_01_000001_create_fakultas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ruang_sidang', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->string('gedung')->nullable();
            $table->integer('kapasitas')->default(0);
            $table->string('lokasi')->nullable();
            $table->string('link_virtual')->nullable();
            $table->boolean('memiliki_proyektor')->default(false);
            $table->boolean('support_online')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ruang_sidang');
    }
};