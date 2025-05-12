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

        // hitung total
        $total = 0;
        $this->nota_beli_items->map(function ($item) use (&$total) {
            $total_sub = $item->harga * $item->jumlah;
            $total += $total_sub;
            return $item;
        });
        $total = $total;

        // hitung total mutasi
        $total_mutasi = 0;
        $this->servis_mutasi->map(function ($item) use (&$total_mutasi) {
            $total_mutasi += ($item->master_mutasi->nominal ?? 0);
            return $item;
        });


        return [
            'id' => $this->id,
            'nama_toko' => $this->nama_toko,
            "nomor_nota" => $this->nomor_nota,
            'tanggal_servis' => $this->tanggal_servis,
            'nota_beli_id' => $this->nota_beli_id,
            'master_armada_id' => $this->master_armada_id,
            'nama_barang' => $this->nama_barang,
            'harga' => $this->harga,
            'jumlah' => $this->jumlah,
            'nopol' => $this->nopol,
            'kategori_servis' => $this->kategori_servis,
            'total' => $total,
            'total_mutasi' => $total_mutasi,
            'status' => $total_mutasi >= $total  ? 'Lunas' : 'Belum Lunas',
            'nota_beli_items' => $this->nota_beli_items,
            'servis_mutasi' => $this->servis_mutasi,
            'master_armada' => $this->master_armada,
        ];
    }
}
