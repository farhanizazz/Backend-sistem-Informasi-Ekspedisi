<?php

namespace App\Http\Resources\LaporanV2;

use Illuminate\Http\Resources\Json\JsonResource;

class HutangPiutangSubkonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $rincian = $this->rincian($this->mutasi_jual()->get());
        $jumlahPembayaran = array_sum(array_column($rincian, 'nominal'));
        return [
            'tanggal' => $this->tanggal_awal,
            'no_transaksi' => $this->no_transaksi,
            'nopol' => $this->nopol_subkon,
            'sopir' => $this->sopir_subkon,
            'penyewa' => $this->penyewa->nama_perusahaan,
            'muatan' => $this->muatan,
            'asal' => $this->asal,
            'tujuan' => $this->tujuan,
            'harga_order' => $this->harga_order_bersih,
            'harga_jual' => $this->harga_jual_bersih,
            'rincian' => $rincian,
            // !biaya tambah_kurang apa ini ?
            'biaya_tambah_kurang' => $jumlahPembayaran,
            'pph' => $this->total_pajak,
            'sisa_hutang' => $this->harga_jual_bersih - $jumlahPembayaran
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
