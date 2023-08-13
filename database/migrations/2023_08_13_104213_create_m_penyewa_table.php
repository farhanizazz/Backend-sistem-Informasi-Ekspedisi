<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMPenyewaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_penyewa', function (Blueprint $table) {
            $table->id();
            $table->string('nama perusahaan', 100);
            $table->text('alamat');
            $table->string('penanggung_jawab',100);
            $table->string('contact_person',15);
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
        Schema::dropIfExists('m_penyewa');
    }
}
