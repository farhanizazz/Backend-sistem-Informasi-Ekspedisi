<?php

namespace App\Models\Transaksi;

use App\Models\Master\ArmadaModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranModel extends Model
{
    use HasFactory;
    protected $table = 'transaksi_pengeluaran_table_';
    protected $fillable = [
        'category',
        'master_armada_id',
        'tgl_transaksi',
        'nama_toko',
        'nomer_nota_beli',
        'nama_tujuan',
        'keterangan',
        'nominal',
        'jumlah',
        'total',
        'status'
    ];

    public function master_armada()
    {
        return $this->belongsTo(ArmadaModel::class, 'master_armada_id');
    }
}
