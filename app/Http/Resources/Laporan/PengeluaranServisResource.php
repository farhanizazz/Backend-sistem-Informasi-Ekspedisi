<?php

namespace App\Http\Resources\Laporan;

use Illuminate\Http\Resources\Json\JsonResource;

class PengeluaranServisResource extends JsonResource
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
            'tanggal' => $this->tanggal_servis,
            'nopol' => $this->nopol ?? null,
            'nama_barang' => $this->nama_barang,
            'nomor_nota' => $this->nomor_nota,
            'keterangan' => $this->keterangan_lain,
            'harga' => $this->harga,
            'jumlah' => $this->jumlah,
            'subtotal' => $this->harga * $this->jumlah,
        ];
    }
}
