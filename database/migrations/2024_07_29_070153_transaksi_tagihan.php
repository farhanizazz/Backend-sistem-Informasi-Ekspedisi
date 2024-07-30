<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TransaksiTagihan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_tagihan', function (Blueprint $table){
            $table->id();
            $table->string("no_tagihan");
            $table->foreignId('m_penyewa_id')->nullable()->constrained('master_penyewa')->comment('Master Penyewa');
            $table->foreignId('master_rekening_id')->nullable()->constrained('master_rekening')->comment('Master Rekening');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_tagihan');
    }
}
