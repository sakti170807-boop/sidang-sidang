<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembimbing', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['user_id']);

            // Baru hapus kolom
            $table->dropColumn('user_id');
        });
    }

    public function down()
    {
        Schema::table('pembimbing', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');

            // Kembalikan foreign key-nya
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
