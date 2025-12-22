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
    Schema::table('pembimbing', function (Blueprint $table) {
        $table->foreignId('pendaftaran_id')->after('id')->constrained('pendaftaran_sidang')->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('pembimbing', function (Blueprint $table) {
        $table->dropForeign(['pendaftaran_id']);
        $table->dropColumn('pendaftaran_id');
    });
}

};
