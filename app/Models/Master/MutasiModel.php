<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\RekeningModel;
use App\Models\Transaksi\OrderModel;

class MutasiModel extends Model
{
    use HasFactory;
    protected $table = 'master_mutasi';
    protected $fillable = [
        'transaksi_order_id',
        'rekening_id',
        'nominal',
        'tanggal_pembayaran',
        'keterangan',
    ];
    public function master_rekening()
    {
        return $this->belongsTo(RekeningModel::class);
    }
    public function transaksi_order()
    {
        return $this->hasOne(OrderModel::class);
    }



}
