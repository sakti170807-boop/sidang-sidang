<?php
// database/migrations/2024_01_01_000001_create_fakultas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'dosen', 'mahasiswa'])->default('mahasiswa')->after('email');
            $table->string('nip')->nullable()->after('role');
            $table->string('nim')->nullable()->after('nip');
            $table->string('nidn')->nullable()->after('nim');
            $table->foreignId('program_studi_id')->nullable()->constrained('program_studi')->nullOnDelete()->after('nidn');
            $table->string('no_telp')->nullable();
            $table->text('alamat')->nullable();
            $table->string('gelar_depan')->nullable();
            $table->string('gelar_belakang')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['program_studi_id']);
            $table->dropColumn(['role', 'nip', 'nim', 'nidn', 'program_studi_id', 'no_telp', 'alamat', 'gelar_depan', 'gelar_belakang', 'is_active']);
        });
    }
};
