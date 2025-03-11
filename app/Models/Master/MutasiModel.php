<?php

namespace App\Models\Master;

use App\Enums\JenisTransaksiMutasiEnum;
use App\Http\Traits\GlobalTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\RekeningModel;
use App\Models\Transaksi\NotaBeliModel;
use App\Models\Transaksi\OrderModel;
use App\Models\Transaksi\ServisMutasiModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MutasiModel extends Model
{
    use HasFactory, GlobalTrait;
    protected $table = 'master_mutasi';
    protected $casts = [
        'jenis_transaksi' => JenisTransaksiMutasiEnum::class
    ];
    protected $fillable = [
        'transaksi_order_id',
        'jenis_transaksi', // 'order' or 'jual
        'master_rekening_id',
        'nominal',
        'tanggal_pembayaran',
        'keterangan',
        'asal_transaksi',
        'model_type',
        'model_id',
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

    public function servis_mutasi()
    {
        return $this->hasOne(ServisMutasiModel::class, 'master_mutasi_id', 'id');
    }

    public function getAll($payload)
    {
        $itemPerPage = $payload['itemPerPage'] ?? 20;

        $data = $this->with(['pembuat', 'master_rekening','servis_mutasi.servis'=> function($q){
            $q->select('id','kategori_servis');
        }, 'transaksi_order' => function ($q) {
                $q->select('id', 'no_transaksi');
            }])
            ->when(isset($payload['transaksi_order_id']) && $payload['transaksi_order_id'], function ($query) use ($payload) {
                $query->where('transaksi_order_id', $payload['transaksi_order_id']);
            })->when(isset($payload['master_rekening_id']) && $payload['master_rekening_id'], function ($query) use ($payload) {
                $query->where('master_rekening_id', $payload['master_rekening_id']);
            })->when(isset($payload['tanggal_pembayaran']) && $payload['tanggal_pembayaran'], function ($query) use ($payload) {
                $query->where('tanggal_pembayaran', $payload['tanggal_pembayaran']);
            })->when(isset($payload['keterangan']) && $payload['keterangan'], function ($query) use ($payload) {
                $query->where('keterangan', $payload['keterangan']);
            })->when(isset($payload['jenis_transaksi']) && $payload['jenis_transaksi'], function ($query) use ($payload) {
                $query->where('jenis_transaksi', $payload['jenis_transaksi']);
            })
            ->orderBy('tanggal_pembayaran', 'desc')->orderBy('created_at', 'DESC')
            ->paginate($itemPerPage);
        
            return $data;
    }

    /**
     * Get detail order
     * @param $id int transaksi_order_id
     * @return array
     */
    public function detailOrder($id)
    {
        $dataOrder = OrderModel::with(['penyewa'])->find($id);
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
            'biaya_lain_harga_jual_arr' => $dataOrder->biaya_lain_harga_jual_arr,
            'thr' => $dataOrder->potongan_wajib,
            'nama_perusahaan' => $dataOrder->penyewa->nama_perusahaan ?? '',
            'sisa_uang_jalan' => (
                $dataOrder->uang_jalan - $dataOrder->potongan_wajib - $dataOrder->mutasi_jalan->sum('nominal') + $biaya_lain_uang_jalan
            ),
            'nopol_subkon' => $dataOrder->nopol_subkon,
            'sopir_subkon' => $dataOrder->sopir_subkon,
            'harga_jual' => $dataOrder->harga_jual,
            'sisa_harga_order' => $dataOrder->harga_order - $dataOrder->mutasi_order->sum('nominal') + $biaya_lain_harga_order - $dataOrder->total_pajak,
            'sisa_harga_jual' => $dataOrder->harga_jual - $dataOrder->mutasi_jual->sum('nominal') + $biaya_lain_harga_jual - $dataOrder->total_pajak,
            'mutasi_jalan' => $dataOrder->mutasi_jalan->sum('nominal'),
            'mutasi_order' => $dataOrder->mutasi_order->sum('nominal'),
            'mutasi_jual' => $dataOrder->mutasi_jual->sum('nominal'),
            'total_pajak' => $dataOrder->total_pajak,
        ];

        return $item;
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function hitungTotalBiayaLain($listBiayaLain = [])
    {
        $total = 0;
        foreach ($listBiayaLain as $key => $value) {
            $total += $value['nominal'];
        }
        return $total;
    }

    public function getMutasiRekening($rekening_id, $itemPerPage = 20)
    {
        try {
            $data = $this->with(['pembuat', 'master_rekening', 'transaksi_order' => function ($q) {
                $q->select('id', 'no_transaksi');
            }])
                ->where('master_rekening_id', $rekening_id)
                ->limit(1)
                ->orderBy('tanggal_pembayaran', 'desc');
            $data = $data->orderByRaw("created_at DESC");
            $sort = "no_transaksi DESC";
            $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;
            return [
                'status' => 'success',
                'data' => $data->paginate($itemPerPage)->appends("sort", $sort)
            ];
        } catch (\Throwable $th) {
            //throw $th;
            return [
                'status' => 'error',
                'message' => $th->getMessage()
            ];
        }
    }
}
