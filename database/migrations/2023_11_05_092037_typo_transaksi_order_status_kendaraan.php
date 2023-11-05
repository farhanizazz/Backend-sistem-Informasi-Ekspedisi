<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TypoTransaksiOrderStatusKendaraan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaksi_order', function(Blueprint $table){
            $table->dropColumn('status_kendaraaan');
            $table->enum('status_kendaraan', ['Sendiri', 'Subkon'])->default('sendiri')->after('tanggal_akhir');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksi_order', function(Blueprint $table){
            $table->dropColumn('status_kendaraan');
            $table->enum('status_kendaraaan', ['Sendiri', 'Subkon'])->default('sendiri')->after('tanggal_akhir');
        });
        //
    }
}
