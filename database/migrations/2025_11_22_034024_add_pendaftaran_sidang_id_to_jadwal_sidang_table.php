<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_sidang', function (Blueprint $table) {
            // Tambahkan kolom untuk relasi ke pendaftaran_sidang
            $table->foreignId('pendaftaran_sidang_id')->nullable()->after('kategori_sidang_id')->constrained('pendaftaran_sidang');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_sidang', function (Blueprint $table) {
            $table->dropForeign(['pendaftaran_sidang_id']);
            $table->dropColumn('pendaftaran_sidang_id');
        });
    }
};
