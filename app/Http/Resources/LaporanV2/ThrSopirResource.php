<?php

namespace App\Http\Resources\LaporanV2;

use App\Models\Master\RekeningModel;
use Illuminate\Http\Resources\Json\JsonResource;

class ThrSopirResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $nopol = $this->armada ? $this->armada->nopol : $this->nopol_subkon;
        $sopir = $this->sopir ? $this->sopir->nama : $this->sopir_subkon;

        return [
            'id' => $this->id,
            'no_transaksi' => $this->no_transaksi,
            'status' => $this->status_kendaraan_sendiri,
            'nopol' => $nopol,
            'sopir' => $sopir,
            'penyewa' => $this->penyewa->nama_perusahaan,
            'muatan' => $this->muatan,
            'asal' => $this->asal,
            'tujuan' => $this->tujuan,
            'potongan_thr' => $this->potongan_wajib
        ];
    }
}
