<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToNotaBeli extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nota_beli', function (Blueprint $table) {
            // $table->foreignId('master_mutasi_id')->nullable()->constrained('master_mutasi')->after('servis_id')->comment('Master mutasi id');
        });

        Schema::table('master_mutasi', function (Blueprint $table) {
            $table->string('asal_transaksi')->after('jenis_transaksi')->default('transaksi_order')->comment('Asal transaksi Contoh: order, servis, dll');
            $table->string('model_type')->default('App\\\Models\\\Transaksi\\\OrderModel')->comment('Model Type')->after('asal_transaksi');
            $table->string('model_id')->nullable()->comment('Model ID')->after('model_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nota_beli', function (Blueprint $table) {
            // $table->dropForeign(['master_mutasi_id']);
            // $table->dropColumn('master_mutasi_id');
        });

        Schema::table('master_mutasi', function (Blueprint $table) {
            $table->dropColumn('asal_transaksi');
            $table->dropColumn('model_id');
            $table->dropColumn('model_type');
        });
    }
}
