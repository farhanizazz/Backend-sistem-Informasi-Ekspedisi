<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMutasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_mutasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_order_id')->nullable()->constrained('transaksi_order');
            $table->foreignId('master_rekening_id')->nullable()->constrained('master_rekening');
            $table->date('tanggal_pembayaran')->nullable();
            $table->integer('nominal');
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('master_mutasi');
    }
}
