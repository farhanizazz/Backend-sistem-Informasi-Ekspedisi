<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveStatusFieldsFromTOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaksi_order', function (Blueprint $table) {
            $table->dropColumn('status_harga_order');
            $table->dropColumn('status_harga_jual');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksi_order', function (Blueprint $table) {
            $table->enum('status_harga_order', ['Pelunasan', 'Dp',])->default('Dp');
            $table->enum('status_harga_jual', ['Pelunasan', 'Dp',])->default('Dp');
        });
    }
}
