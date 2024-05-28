<?php

namespace App\Models\Transaksi;

use App\Models\Master\MutasiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaBeliModel extends Model
{
    use HasFactory;
    protected $table = 'nota_beli';
    protected $fillable = [
        'nama_barang',
        'harga',
        'jumlah',
        'servis_id',
    ];
}
