<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiTagihanDetModel extends Model
{
    use HasFactory;

    protected $table = "transaksi_tagihan_det";
    protected $fillable = [
        'transaksi_tagihan_id',
        'transaksi_order_id',
    ];

    public function transaksi_tagihan()
    {
        return $this->belongsTo(TransaksiTagihanModel::class);
    }

    public function transaksi_order()
    {
        return $this->belongsTo(OrderModel::class);
    }
}
