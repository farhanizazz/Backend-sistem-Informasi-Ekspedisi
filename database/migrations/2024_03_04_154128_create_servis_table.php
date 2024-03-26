<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servis', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_servis');
            $table->string('nama_toko');
            $table->foreignId('nota_beli_id')->nullable()->constrained('nota_beli');
            $table->foreignId('master_armada_id')->nullable()->constrained('master_armada');
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
        Schema::dropIfExists('servis');
    }
}
