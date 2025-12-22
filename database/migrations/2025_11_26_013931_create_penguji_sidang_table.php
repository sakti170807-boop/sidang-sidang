<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penguji_sidang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_sidang_id')->constrained('jadwal_sidang')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->enum('peran', ['ketua', 'sekretaris', 'penguji_1', 'penguji_2', 'pembimbing_1', 'pembimbing_2']);
            $table->timestamps();
            
            $table->unique(['jadwal_sidang_id', 'dosen_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penguji_sidang');
    }
};