<?php

use App\Enums\JenisTransaksiMutasiEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeEnumToStringOnMutasiModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_mutasi', function (Blueprint $table) {
            $table->string('jenis_transaksi')->default(JenisTransaksiMutasiEnum::ORDER->value)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_mutasi', function (Blueprint $table) {
            $table->enum('jenis_transaksi', ['order', 'jual', 'uang_jalan'])->change();
        });
    }
}
