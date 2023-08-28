<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiPengeluaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * category' => 'required|in:kategori_servis,lain_lain',

     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_pengeluaran_table_', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['servis', 'lain_lain'])->default('servis');
            $table->foreignId('master_armada_id')->constrained('master_armada');
            $table->string('tgl_transaksi');
            $table->string('nama_toko')->nullable();
            $table->string('nomer_nota_beli')->nullable();
            $table->string('nama_tujuan')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('nominal')->nullable();
            $table->string('jumlah')->nullable();
            $table->string('total')->nullable();
            $table->enum('status', ['lunas', 'DP', 'belum_bayar'])->default('belum_bayar');
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
        Schema::dropIfExists('transaksi_pengeluaran_table_');
    }
}
