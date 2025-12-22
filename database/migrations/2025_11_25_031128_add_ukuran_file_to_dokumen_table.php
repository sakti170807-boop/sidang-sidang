<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('dokumen', function (Blueprint $table) {
            $table->bigInteger('ukuran_file')->unsigned()->nullable()->after('nama_file');
        });
    }

    public function down()
    {
        Schema::table('dokumen', function (Blueprint $table) {
            $table->dropColumn('ukuran_file');
        });
    }
};