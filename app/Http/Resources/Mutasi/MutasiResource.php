<?php

namespace App\Http\Resources\Mutasi;

use App\Models\Transaksi\OrderModel;
use Illuminate\Http\Resources\Json\JsonResource;

class MutasiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $payload = $request->all();
        $data = parent::toArray($request);

        // get detail order
        if (isset($payload['transaksi_order_id']) && $payload['transaksi_order_id'] && count($data) > 0) {
            $data['detail'] = $this->detailOrder($data['transaksi_order_id']);
        }

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

    public function hitungTotalBiayaLain($listBiayaLain = [])
    {
        $total = 0;
        foreach ($listBiayaLain as $key => $value) {
            $total += $value['nominal'];
        }
        return $total;
    }

}
