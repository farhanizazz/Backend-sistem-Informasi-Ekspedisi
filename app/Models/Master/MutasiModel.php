<?php

namespace App\Models\Master;

use App\Http\Traits\GlobalTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\RekeningModel;
use App\Models\Transaksi\OrderModel;
use App\Models\User;

class MutasiModel extends Model
{
    use HasFactory, GlobalTrait;
    protected $table = 'master_mutasi';
    protected $fillable = [
        'transaksi_order_id',
        'jenis_transaksi', // 'order' or 'jual
        'master_rekening_id',
        'nominal',
        'tanggal_pembayaran',
        'keterangan',
        'created_by'
    ];
    public function master_rekening()
    {
        return $this->belongsTo(RekeningModel::class);
    }
    public function transaksi_order()
    {
        return $this->hasOne(OrderModel::class, 'id', 'transaksi_order_id');
    }

    public function getAll($payload){
        $data = $this->
        with(['pembuat', 'master_rekening', 'transaksi_order' => function($q){
            $q->select('id', 'no_transaksi');
        }])
        ->when(isset($payload['transaksi_order_id']) && $payload['transaksi_order_id'], function($query) use($payload){
            $query->where('transaksi_order_id', $payload['transaksi_order_id']);
        })->when(isset($payload['master_rekening_id']) && $payload['master_rekening_id'], function($query) use($payload){
            $query->where('master_rekening_id', $payload['master_rekening_id']);
        })->when(isset($payload['tanggal_pembayaran']) && $payload['tanggal_pembayaran'], function($query) use($payload){
            $query->where('tanggal_pembayaran', $payload['tanggal_pembayaran']);
        })->when(isset($payload['keterangan']) && $payload['keterangan'], function($query) use($payload){
            $query->where('keterangan', $payload['keterangan']);
        })->when(isset($payload['jenis_transaksi']) && $payload['jenis_transaksi'], function($query) use($payload){
            $query->where('jenis_transaksi', $payload['jenis_transaksi']);
        })
        ->orderBy('tanggal_pembayaran', 'desc')->get();

        // get detail order
        if (isset($payload['transaksi_order_id']) && $payload['transaksi_order_id'] && $data->count() > 0) {
            $data = $data->map(function($item){
                $item['detail'] = $this->detailOrder($item->transaksi_order_id);
                return $item;
            });
        }
        return $data;
    }

    /**
     * Get detail order
     * @param $id int transaksi_order_id
     * @return array
     */
    public function detailOrder($id){
        $dataOrder = OrderModel::find($id);
        $biaya_lain_uang_jalan = empty($dataOrder->biaya_lain_uang_jalan) || !$dataOrder->biaya_lain_uang_jalan ? 0 : $this->hitungTotalBiayaLain($dataOrder->biaya_lain_uang_jalan);
        $biaya_lain_harga_order = empty($dataOrder->biaya_lain_harga_order) || !$dataOrder->biaya_lain_harga_order ? 0 : $this->hitungTotalBiayaLain($dataOrder->biaya_lain_harga_order);
        $biaya_lain_harga_jual = empty($dataOrder->biaya_lain_harga_jual) || !$dataOrder->biaya_lain_harga_jual ? 0 : $this->hitungTotalBiayaLain($dataOrder->biaya_lain_harga_jual);
        $item = [
            'no_transaksi' => $dataOrder->no_transaksi,
            'harga_order' => $dataOrder->harga_order,
            'biaya_lain_harga_order_arr' => $dataOrder->biaya_lain_harga_order_arr,
            'sisa_tagihan' => $dataOrder->sisa_tagihan,
            'muatan' => $dataOrder->muatan,
            'asal'  => $dataOrder->asal,
            'tujuan' => $dataOrder->tujuan,
            'uang_jalan' => $dataOrder->uang_jalan,
            'biaya_lain_uang_jalan_arr' => $dataOrder->biaya_lain_uang_jalan_arr,
            'thr' => $dataOrder->potongan_wajib,
            'sisa_uang_jalan' => (
                $dataOrder->uang_jalan - $dataOrder->potongan_wajib - $dataOrder->mutasi_jalan->sum('nominal') + $biaya_lain_uang_jalan
            ),
            'sisa_harga_order' => $dataOrder->harga_order - $dataOrder->mutasi_order->sum('nominal') + $biaya_lain_harga_order - $dataOrder->total_pajak,
            'sisa_harga_jual' => $dataOrder->harga_jual - $dataOrder->mutasi_jual->sum('nominal') + $biaya_lain_harga_jual - $dataOrder->total_pajak,
            'mutasi_jalan' => $dataOrder->mutasi_jalan->sum('nominal'),
            'mutasi_order' => $dataOrder->mutasi_order->sum('nominal'),
            'mutasi_jual' => $dataOrder->mutasi_jual->sum('nominal'),
        ];
     
        return $item;
    }

    public function pembuat(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function hitungTotalBiayaLain($listBiayaLain = [])
    {
      $tambahanModel = new RekeningModel();
      $listId = array_column($listBiayaLain, 'm_tambahan_id');
      $listRekening = $tambahanModel->whereIn('id', $listId)->get();
      
      foreach ($listBiayaLain as $key => $value) {
        foreach ($listRekening as $key2 => $value2) {
          if ($value['m_tambahan_id'] == $value2->id) {
            $listBiayaLain[$key]["nominal"] *= $this->getSifatRekening($value2->sifat);
          }
        }
      }
  
      $total = 0;
      foreach ($listBiayaLain as $key => $value) {
        $total += $value['nominal'];
      }
      return $total;
    }

}
