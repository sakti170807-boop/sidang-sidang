<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('jadwal_sidang', function (Blueprint $table) {
        $table->dateTime('tanggal_waktu')->nullable()->after('id');
    });
}

public function down()
{
    Schema::table('jadwal_sidang', function (Blueprint $table) {
        $table->dropColumn('tanggal_waktu');
    });
}

};
