<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHutangSopirTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hutang_sopir', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('tgl_transaksi');
            $table->foreignId('master_sopir_id')->constrained('master_sopir');
            $table->integer('nominal_trans');
            $table->text('ket_trans')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hutang_sopir');
    }
}
