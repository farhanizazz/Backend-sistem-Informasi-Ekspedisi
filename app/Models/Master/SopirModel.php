<?php

namespace App\Models\Master;

use App\Models\Transaksi\HutangSopirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SopirModel extends Model
{
    use HasFactory;
    protected $table = "master_sopir";
    protected $fillable = [
        'nama',
        'alamat',
        'ktp',
        'sim',
        'keterangan',
        'tanggal_gabung',
        'nomor_hp',
        'status'
    ];

    public function getKeteranganAttribute()
    {
        return $this->attributes['keterangan'] ?? 'Tidak ada keterangan';
    }


    public function hutangs()
    {
        return $this->hasMany(HutangSopirModel::class, 'master_sopir_id', 'id');
    }
}
