<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::table('jadwal_sidang', function (Blueprint $table) {
        $table->unsignedBigInteger('kategori_sidang_id')->nullable()->change();
    });
}

public function down()
{
    Schema::table('jadwal_sidang', function (Blueprint $table) {
        $table->unsignedBigInteger('kategori_sidang_id')->nullable(false)->change();
    });
}
};
