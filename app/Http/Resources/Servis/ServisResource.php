<?php

namespace App\Http\Resources\Servis;

use Illuminate\Http\Resources\Json\JsonResource;

class ServisResource extends JsonResource
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
            'nama_toko' => $this->nama_toko,
            'tanggal_servis' => $this->tanggal_servis,
            'nota_beli_id' => $this->nota_beli_id,
            'm_armada_id' => $this->m_armada_id,
            'nama_barang' => $this->nama_barang,
            'harga' => $this->harga,
            'jumlah' => $this->jumlah,
            'nopol' => $this->nopol,

        ];
    }
}
