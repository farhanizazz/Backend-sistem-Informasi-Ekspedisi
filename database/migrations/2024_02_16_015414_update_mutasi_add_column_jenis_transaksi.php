<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMutasiAddColumnJenisTransaksi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_mutasi', function(Blueprint $table){
            $table->enum("jenis_transaksi",["order", "jual"])->default("order")->after("nominal");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("master_mutasi", function(Blueprint $table){
            $table->dropColumn("jenis_transaksi");
        });
    }
}
