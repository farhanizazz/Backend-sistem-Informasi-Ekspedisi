<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TambahKolomPadaTableOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaksi_order', function(Blueprint $table){
            $table->text("catatan_surat_jalan")->nullable()->after("status_surat_jalan");
            $table->string("nopol_subkon")->nullable()->after("m_armada_id");
            $table->string("sopir_subkon")->nullable()->after("m_sopir_id");
            $table->string("nomor_sj_po_do")->nullable()->after("no_transaksi"); 
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
            $table->dropColumn("catatan_surat_jalan");
            $table->dropColumn("nopol_subkon");
            $table->dropColumn("sopir_subkon");
            $table->dropColumn("nomor_sj_po_do");
        });
    }
}
