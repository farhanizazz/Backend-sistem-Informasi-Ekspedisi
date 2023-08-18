<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMRekeningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_rekening', function (Blueprint $table) {
            $table->id();
            $table->integer('biaya_kuli');
            $table->integer('biaya akomodasi');
            $table->integer('biaya_tol');
            $table->integer('claim');
            $table->date('tgl_transaksi')->nullable();
            $table->integer('brg_rusak');
            $table->integer('total')->nullable();
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
        Schema::dropIfExists('master_rekening');
    }
}
