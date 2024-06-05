<?php

namespace App\Http\Resources\PotonganWajib;

use Illuminate\Http\Resources\Json\JsonResource;

class PotonganWajibResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'no_transaksi' => $this->no_transaksi,
            'tanggal_awal' => $this->tanggal_awal,
            'tanggal_akhir' => $this->tanggal_akhir,
            'status_surat_jalan' => $this->status_surat_jalan,
            'm_sopir_id' => $this->m_sopir_id,
            'm_penyewa_id' => $this->m_penyewa_id,
            'muatan' => $this->muatan,
            'asal' => $this->asal,
            'tujuan' => $this->tujuan,
            'potongan_wajib' => $this->potongan_wajib,
        ];
    }

}
