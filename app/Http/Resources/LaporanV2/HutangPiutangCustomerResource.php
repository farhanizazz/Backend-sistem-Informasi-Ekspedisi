<?php

namespace App\Http\Resources\LaporanV2;

use Illuminate\Http\Resources\Json\JsonResource;

class HutangPiutangCustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $rincian = $this->rincian($this->mutasi_order()->get());
        $jumlahPembayaran = array_sum(array_column($rincian, 'nominal'));

        return [
            'tanggal' => $this->tanggal_awal,
            'no_transaksi' => $this->no_transaksi,
            'nopol' => $this->armada ? $this->armada->nopol : $this->nopol_subkon,
            'sopir' => $this->sopir ? $this->sopir->nama : $this->sopir_subkon,
            'penyewa' => $this->penyewa->nama_perusahaan,
            'muatan' => $this->muatan,
            'asal' => $this->asal,
            'tujuan' => $this->tujuan,
            'rincian' => $rincian,
            'harga_order' => $this->harga_order_bersih,
            'biaya_tambah_kurang' => $jumlahPembayaran,
            'pph' => $this->total_pajak,
            'sisa_tagihan' => $this->harga_order_bersih - $jumlahPembayaran
        ];
    }

    public function rincian($listRincianJual)
    {
        $rincian = [];
        foreach ($listRincianJual as $rincianJual) {
            $rincian[] = [
                'tanggal' => $rincianJual->tanggal_pembayaran,
                'keterangan' => $rincianJual->keterangan,
                'nominal' => $rincianJual->nominal,
            ];
        }

        return $rincian;
    }
}
