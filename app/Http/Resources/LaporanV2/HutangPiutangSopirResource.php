<?php

namespace App\Http\Resources\LaporanV2;

use App\Models\Master\TambahanModel;
use Illuminate\Http\Resources\Json\JsonResource;

class HutangPiutangSopirResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $totalBiayaTambahan = 0;
        $totalBiayaKurang = 0;

        foreach ($this->biayaLainUangJalanArr as $biayaLainUangJalan) {
            if ($biayaLainUangJalan['sifat'] == TambahanModel::SIFAT_MENAMBAHKAN) {
                $totalBiayaTambahan += $biayaLainUangJalan['nominal'];
            } else if ($biayaLainUangJalan['sifat'] == TambahanModel::SIFAT_MENGURANGI) {
                $totalBiayaKurang += $biayaLainUangJalan['nominal'];
            }
        }

        $totalUangJalan = $totalBiayaTambahan + $totalBiayaKurang + $this->uang_jalan - $this->potongan_wajib;

        $rincian = $this->rincian($this->mutasi_jalan()->get());
        $jumlahPembayaran = array_sum(array_column($rincian, 'nominal'));

        return [
            'tanggal' => $this->tanggal_awal,
            'no_transaksi' => $this->no_transaksi,
            'penyewa' => $this->penyewa->nama_perusahaan,
            'muatan' => $this->muatan,
            'asal' => $this->asal,
            'tujuan' => $this->tujuan,

            'uang_jalan' => $this->uang_jalan,
            'biaya_tambahan' => $totalBiayaTambahan,
            'biaya_kurang' => $totalBiayaKurang,
            'pot_thr' => $this->potongan_wajib,
            'total_uang_jalan' => $totalUangJalan,

            'rincian' => $rincian,

            'jumlah_pembayaran' => $jumlahPembayaran,
            'sisa_uang_jalan' => $totalUangJalan - $jumlahPembayaran
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
