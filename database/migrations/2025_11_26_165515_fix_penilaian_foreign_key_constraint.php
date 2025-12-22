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
        // Drop the existing foreign key constraint
        Schema::table('penilaian', function (Blueprint $table) {
            $table->dropForeign(['penguji_id']);
        });

        // Add the new foreign key constraint referencing penguji_sidang table
        Schema::table('penilaian', function (Blueprint $table) {
            $table->foreign('penguji_id')->references('id')->on('penguji_sidang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the new foreign key constraint
        Schema::table('penilaian', function (Blueprint $table) {
            $table->dropForeign(['penguji_id']);
        });

        // Restore the old foreign key constraint referencing penguji table
        Schema::table('penilaian', function (Blueprint $table) {
            $table->foreign('penguji_id')->references('id')->on('penguji')->onDelete('cascade');
        });
    }
};