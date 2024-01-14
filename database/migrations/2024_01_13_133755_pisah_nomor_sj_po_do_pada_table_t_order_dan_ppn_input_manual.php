<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PisahNomorSjPoDoPadaTableTOrderDanPpnInputManual extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaksi_order', function(Blueprint $table){
            $table->dropColumn("nomor_sj_po_do");
            $table->string("nomor_sj")->nullable()->after("no_transaksi");
            $table->string("nomor_po")->nullable()->after("nomor_sj");
            $table->string("nomor_do")->nullable()->after("nomor_po");
            $table->integer("ppn")->nullable()->after("nomor_do");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("transaksi_order", function(Blueprint $table){
            $table->dropColumn("nomor_sj");
            $table->dropColumn("nomor_po");
            $table->dropColumn("nomor_do");
            $table->dropColumn("ppn");
        });
    }
}
