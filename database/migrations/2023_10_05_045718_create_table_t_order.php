<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_order', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_awal')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->enum('status_kendaraaan', ['Sendiri', 'Subkon'])->default('sendiri');
            $table->enum('status_kendaraan_sendiri', ['Berangkat', 'Pulang', 'Kontrak', 'Kota-Kota'])->nullable();
            $table->string('no_transaksi')->nullable();
            $table->enum('status_surat_jalan', ['Sopir', 'Kantor', 'Selesai'])->default('Sopir');
            $table->foreignId('m_penyewa_id')->nullable()->constrained('master_penyewa');
            $table->string('muatan')->nullable();
            $table->foreignId('m_armada_id')->nullable()->constrained('master_armada');
            $table->foreignId('m_sopir_id')->nullable()->constrained('master_sopir');
            $table->string('asal')->nullable();
            $table->string('tujuan')->nullable();
            $table->bigInteger('harga_order')->nullable();
            $table->bigInteger('bayar_harga_order')->nullable();
            $table->bigInteger('harga_order_bersih')->nullable();
            $table->enum('status_harga_order', ['Pelunasan', 'Dp',])->default('Dp');
            $table->json('biaya_lain_harga_order')->nullable();
            $table->enum('status_pajak', ['ya', 'tidak'])->default('ya');
            $table->bigInteger('total_pajak')->nullable();
            $table->bigInteger('setor')->nullable();
            $table->bigInteger('uang_jalan')->nullable();
            $table->bigInteger('uang_jalan_bersih')->nullable();
            $table->bigInteger('potongan_wajib')->nullable();
            $table->json('biaya_lain_uang_jalan')->nullable();
            $table->foreignId('m_subkon_id')->nullable()->constrained('master_subkon');
            $table->bigInteger('harga_jual')->nullable();
            $table->bigInteger('bayar_harga_jual')->nullable();
            $table->bigInteger('harga_jual_bersih')->nullable();
            $table->enum('status_harga_jual', ['Pelunasan', 'Dp',])->default('Dp');
            $table->json('biaya_lain_harga_jual')->nullable();
            $table->string('keterangan')->nullable();
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
        Schema::dropIfExists('transaksi_order');
    }
}
