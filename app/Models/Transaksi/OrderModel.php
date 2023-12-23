<?php

namespace App\Models\Transaksi;

use App\Models\Master\RekeningModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    use HasFactory;

    protected $table = 'transaksi_order';
    protected $fillable = [
        'tanggal_awal',
        'tanggal_akhir',
        'status_kendaraan',
        'status_kendaraan_sendiri',
        'no_transaksi',
        'status_surat_jalan',
        'm_penyewa_id',
        'muatan',
        'm_armada_id',
        'm_sopir_id',
        'asal',
        'tujuan',
        'harga_order',
        'harga_order_bersih',
        'bayar_harga_order',
        'status_harga_order',
        'biaya_lain_harga_order',
        'status_pajak',
        'total_pajak',
        'setor',
        'uang_jalan',
        'uang_jalan_bersih',
        'potongan_wajib',
        'biaya_lain_uang_jalan',
        'm_subkon_id',
        'harga_jual',
        'bayar_harga_jual',
        'harga_jual_bersih',
        'status_harga_jual',
        'biaya_lain_harga_jual',
        'keterangan'
    ];

    protected $casts = [
        'biaya_lain_harga_order' => 'array',
        'biaya_lain_uang_jalan' => 'array',
        'biaya_lain_harga_jual' => 'array',
    ];

    protected $appends = [
        'sisa_tagihan',
        'sisa_hutang_ke_subkon',
        'biaya_lain_harga_order_arr',
        'biasa_lain_harga_jual_arr',
        'biaya_lain_uang_jalan_arr'
    ];

    public function penyewa()
    {
        return $this->belongsTo('App\Models\Master\PenyewaModel', 'm_penyewa_id');
    }

    public function armada()
    {
        return $this->belongsTo('App\Models\Master\ArmadaModel', 'm_armada_id');
    }

    public function sopir()
    {
        return $this->belongsTo('App\Models\Master\SopirModel', 'm_sopir_id');
    }

    public function subkon()
    {
        return $this->belongsTo('App\Models\Master\SubkonModel', 'm_subkon_id');
    }

    public function getBiayaLainHargaOrderArrAttribute()
    {
        if ($this->biaya_lain_harga_order == null) {
            return [];
        }
        return array_map(function($data){
                    $rekeningData = RekeningModel::where('id',$data['m_rekening_id'])->first();
                    $sifat = $rekeningData->sifat ?? '';
                    $nama = $rekeningData->nama ?? '';
                    return array_merge($data,['sifat' => $sifat, 'nama' => $nama]);
                },$this->biaya_lain_harga_order);
    }

    public function getBiayaLainUangJalanArrAttribute()
    {
        if ($this->biaya_lain_uang_jalan == null) {
            return [];
        }
        return array_map(function($data){
            $rekeningData = RekeningModel::where('id',$data['m_rekening_id'])->first();
            $sifat = $rekeningData->sifat ?? '';
            $nama = $rekeningData->nama ?? '';
            return array_merge($data,['sifat' => $sifat, 'nama' => $nama]);
        },$this->biaya_lain_uang_jalan);
    }

    public function getBiayaLainHargaJualArrAttribute()
    {
        if ($this->biaya_lain_harga_jual == null) {
            return [];
        }
        return array_map(function($data){
            $rekeningData = RekeningModel::where('id',$data['m_rekening_id'])->first();
            $sifat = $rekeningData->sifat ?? '';
            $nama = $rekeningData->nama ?? '';
            return array_merge($data,['sifat' => $sifat, 'nama' => $nama]);
        },$this->biaya_lain_harga_jual);
    }

    public function getSisaTagihanAttribute()
    {
        $sisa_tagihan = $this->harga_order_bersih - $this->bayar_harga_order;
        return $sisa_tagihan;
    }

    public function getSisaHutangKeSubkonAttribute()
    {
        $sisa_hutang_ke_subkon = $this->harga_jual_bersih - $this->bayar_harga_jual;
        return $sisa_hutang_ke_subkon;
    }
}
