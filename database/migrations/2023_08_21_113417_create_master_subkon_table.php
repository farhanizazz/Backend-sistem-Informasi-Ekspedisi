<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterSubkonTable extends Migration
{
    /**
     * Run the migrations.
     *

     * @return void
     */
    public function up()
    {
        Schema::create('master_subkon', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("nama_perusahaan");
            $table->string("alamat");
            $table->string("penanggung_jawab");
            $table->text("ket")->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_subkon');
    }
}
