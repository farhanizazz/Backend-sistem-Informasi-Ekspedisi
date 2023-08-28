<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterArmadaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_armada', function (Blueprint $table) {
            $table->id();
            $table->string("nopol", 20);
            $table->string("merk", 50);
            $table->string("jenis", 50);
            $table->date("tgl_stnk");
            $table->date("tgl_uji_kir");
            $table->enum('status_stnk',['aktif','nonaktif','pra-nonaktif'])->default('aktif')->comment('Aktif adalah status stnk yang masih aktif, nonaktif adalah status stnk yang sudah tidak aktif, pra-nonaktif adalah status stnk yang akan segera tidak aktif');
            $table->enum('status_uji_kir',['aktif','nonaktif','pra-nonaktif'])->default('aktif')->comment('Aktif adalah status kir yang masih aktif, nonaktif adalah status kir yang sudah tidak aktif, pra-nonaktif adalah status kir yang akan segera tidak aktif');
            $table->text("keterangan")->nullable(true);
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
        Schema::dropIfExists('master_armada');
    }
}
