<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\ArmadaModel;
use App\Models\Transaksi\NotaBeliModel;

class ServisModel extends Model
{
    use HasFactory;
    protected $table = 'servis';
    protected $fillable = [
        'nama_toko',
        'tanggal_servis',
        'nota_beli_id',
        'master_armada_id',
    ];
    public function master_armada()
    {
        return $this->belongsTo(ArmadaModel::class);
    }
    
       // Define the relationship with NotaBeli
    public function nota_beli()
    {
        return $this->hasMany(NotaBeliModel::class);
    }
    public function getAll($payload){
        $data =$this->with(['master_armada' => function ($query) {
            $query->select('id', 'nopol');
        }])->when(isset($payload['nota_beli_id']) && $payload['nota_beli_id'], function($query) use($payload){
            $query->where('nota_beli_id', $payload['nota_beli_id']);
        })->when(isset($payload['nama_toko'])&& $payload['nama_toko'],function($query) use($payload){
            $query->where('nama_toko',$payload['nama_toko']);
        })->when(isset($payload['tanggal_servis'])&& $payload['tanggal_servis'],function($query) use($payload){
            $query->where('tanggal_servis',$payload['tanggal_servis']);
        })->get();

        return $data;
    }


}
