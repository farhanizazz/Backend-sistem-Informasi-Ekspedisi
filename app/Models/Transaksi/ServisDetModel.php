<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServisDetModel extends Model
{
    use HasFactory;
    protected $table = 'servis_det';
    protected $fillable = [
        'servis_id',
        'nama_barang',
        'harga',
        'jumlah',
    ];
}
