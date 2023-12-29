<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiModel extends Model
{
    use HasFactory;
    protected $table = 'master_mutasi';
    protected $fillable = [
        'transaksi_order_id',
        'nominal',
        'tanggal_pembayaran',
        'keterangan',
    ];
}
