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
        Schema::table('jadwal_sidang', function (Blueprint $table) {
            // Hapus kolom lama jika ada
            if (Schema::hasColumn('jadwal_sidang', 'tanggal_waktu')) {
                $table->dropColumn('tanggal_waktu');
            }
            if (Schema::hasColumn('jadwal_sidang', 'durasi_menit')) {
                $table->dropColumn('durasi_menit');
            }

            // Tambah kolom baru
            if (!Schema::hasColumn('jadwal_sidang', 'tanggal')) {
                $table->date('tanggal')->after('ruang_sidang_id');
            }
            if (!Schema::hasColumn('jadwal_sidang', 'waktu_mulai')) {
                $table->time('waktu_mulai')->after('tanggal');
            }
            if (!Schema::hasColumn('jadwal_sidang', 'waktu_selesai')) {
                $table->time('waktu_selesai')->after('waktu_mulai');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_sidang', function (Blueprint $table) {
            $table->dropColumn(['tanggal', 'waktu_mulai', 'waktu_selesai']);
            $table->dateTime('tanggal_waktu')->after('ruang_sidang_id');
            $table->integer('durasi_menit')->default(120);
        });
    }
};