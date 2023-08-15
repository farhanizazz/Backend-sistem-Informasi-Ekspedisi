<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MSopir extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_sopir', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->text('alamat');
            $table->string('KTP', 16);
            $table->string('SIM', 12);
            $table->string('phone_number',13);
            $table->text('keterangan')->nullable();
            $table->date('tanggal_gabung')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('master_sopir');
    }
}
