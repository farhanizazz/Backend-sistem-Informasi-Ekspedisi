<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Master\SopirModel;

class HutangSopirModel extends Model
{
    use HasFactory;
    protected $table = 'hutang_sopir';
    protected $fillable = [
        'tgl_transaksi',
        'master_sopir_id',
        'nominal_trans',
        'ket_trans'
    ];
    public function master_sopir()
    {
        return $this->belongsTo(SopirModel::class, 'master_sopir_id', 'id');
    }

    public function getKetTransAttribute(){
        return $this->attributes['ket_trans'] ?? 'Tidak ada keterangan';
    }
    
}
