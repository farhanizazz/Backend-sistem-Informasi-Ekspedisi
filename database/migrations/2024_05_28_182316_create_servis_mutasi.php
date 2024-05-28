<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServisMutasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servis_mutasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servis_id')->nullable()->constrained('servis')->comment('Servis ID');
            $table->foreignId('master_mutasi_id')->nullable()->constrained('master_mutasi')->comment('Master mutasi id');
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
        Schema::dropIfExists('servis_mutasi');
    }
}
