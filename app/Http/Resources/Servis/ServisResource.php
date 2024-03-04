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
            'nopol' => $this->nopol,
            'armada' => $this->armada,
            'merged_servis_nota_beli' => $this->mergedServisNotaBeli,
        ];
    }
}
