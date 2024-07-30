<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TransaksiTagihanDet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_tagihan_det', function (Blueprint $table){
            $table->id();
            $table->foreignId('transaksi_tagihan_id')->nullable()->constrained('transaksi_tagihan')->comment('Transaksi Tagihan');
            $table->foreignId('transaksi_order_id')->nullable()->constrained('transaksi_order')->comment('Transaksi Order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_tagihan_det');
    }
}
