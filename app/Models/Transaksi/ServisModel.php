<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi\NotaBeliModel;
use App\Models\Master\ArmadaModel;

class ServisModel extends Model
{
    use HasFactory;
    protected $table = 'servis';
    protected $fillable = [
        'nama_toko',
        'nopol'
    ];
    public function armada()
    {
        return $this->belongsTo('App\Models\Master\ArmadaModel', 'nopol', 'nopol');
    }
    
    public function getMergedServisNotaBeliAttribute()
    {
        if ($this->servis == null || $this->nota_beli == null) {
            return [];
        }

        return array_map(function($data){
            $notaBeliData = NotaBeliModel::where('no', $data['no'])->first();
            $nama_barang = $notaBeliData->nama_barang ?? '';
            $harga = $notaBeliData->harga ?? '';
            $jumlah = $notaBeliData->jumlah ?? '';
            return array_merge($data, ['nama_barang' => $nama_barang, 'harga' => $harga, 'jumlah' => $jumlah]);
        }, $this->servis);
    }
       // Define the relationship with NotaBeli
       public function notaBeli()
       {
           return $this->hasMany('App\Models\Transaksi\NotaBeli', 'nota_beli_id');
       }

}
