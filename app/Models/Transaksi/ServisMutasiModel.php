<?php

namespace App\Models\Transaksi;

use App\Models\Master\MutasiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServisMutasiModel extends Model
{
    use HasFactory;
    protected $table = 'servis_mutasi';
    protected $fillable = [
        'servis_id',
        'master_mutasi_id',
    ];

    public function servis()
    {
        return $this->belongsTo(ServisModel::class);
    }

    public function master_mutasi()
    {
        return $this->belongsTo(MutasiModel::class);
    }
}
