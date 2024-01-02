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
        'master_rekening_id',
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

    public function getAll($payload){
        $data = $this->when(isset($payload['transaksi_order_id']) && $payload['transaksi_order_id'], function($query) use($payload){
            $query->where('transaksi_order_id', $payload['transaksi_order_id']);
        })->when(isset($payload['rekening_id']) && $payload['rekening_id'], function($query) use($payload){
            $query->where('rekening_id', $payload['rekening_id']);
        })->when(isset($payload['tanggal_pembayaran']) && $payload['tanggal_pembayaran'], function($query) use($payload){
            $query->where('tanggal_pembayaran', $payload['tanggal_pembayaran']);
        })->when(isset($payload['keterangan']) && $payload['keterangan'], function($query) use($payload){
            $query->where('keterangan', $payload['keterangan']);
        })->get();
        return $data;
    }



}
