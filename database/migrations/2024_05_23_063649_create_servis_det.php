<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServisDet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('servis_det', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('servis_id')->nullable()->constrained('servis');
        //     $table->string("nama_barang");
        //     $table->string("harga");
        //     $table->integer("jumlah");
        //     $table->timestamps();
        // });

        Schema::table("servis", function (Blueprint $table){
            $table->dropForeign(['nota_beli_id']);
            $table->string('kategori_servis')->after('nama_toko');
            $table->string('nama_tujuan_lain')->nullable()->after('kategori_servis')->comment('Nama tujuan servis lain lain');
            $table->string('keterangan_lain')->nullable()->after('nama_tujuan_lain')->comment('Keterangan servis lain lain');
            $table->string('nominal_lain')->nullable()->after('keterangan_lain')->comment('Nominal servis lain lain');
            $table->integer("jumlah_lain")->nullable()->after('nominal_lain')->comment('Jumlah servis lain lain');
            $table->integer("total_lain")->nullable()->after('jumlah_lain')->comment('Total harga servis');
        });

        Schema::table("nota_beli", function (Blueprint $table){
            $table->foreignId('servis_id')->nullable()->constrained('servis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('servis_det');

        Schema::table("servis", function (Blueprint $table){
            $table->foreignId('nota_beli_id')->nullable()->constrained('nota_beli');
        });

        Schema::table("nota_beli", function (Blueprint $table){
            $table->dropForeign(['servis_id']);
        });
    }
}
