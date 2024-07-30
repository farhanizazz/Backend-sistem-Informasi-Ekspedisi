<?php

namespace App\Models\Transaksi;

use App\Models\Master\PenyewaModel;
use App\Models\Master\RekeningModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TransaksiTagihanDet;

class TransaksiTagihanModel extends Model
{
    use HasFactory;

    protected $table = "transaksi_tagihan";
    protected $fillable = [
        'singkatan',
        'no_tagihan',
        'm_penyewa_id',
        'master_rekening_id',
    ];

    public function m_penyewa()
    {
        return $this->belongsTo(PenyewaModel::class);
    }

    public function master_rekening()
    {
        return $this->belongsTo(RekeningModel::class);
    }
    
    public function transaksi_tagihan_det()
    {
        return $this->hasMany(TransaksiTagihanDetModel::class, 'transaksi_tagihan_id');
    }

    public function getDataWithPagination($payload, $itemPerPage = 20){
        $data =$this->with(['m_penyewa' => function ($query) {
            $query->select('id', 'nama_perusahaan', 'alamat');
        }, 'master_rekening', 'transaksi_tagihan_det.transaksi_order']);
        $sort = "created_at DESC";
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;
        return $data->paginate($itemPerPage)->appends("sort", $sort);
    }
}
