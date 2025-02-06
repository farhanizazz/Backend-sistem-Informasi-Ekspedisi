<?php

namespace App\Http\Resources\LaporanV2;

use Illuminate\Http\Resources\Json\JsonResource;

class ArmadaRugiLabaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $parent = parent::toArray($request);

        $parent['pemasukan_setor'] = intval($this->pemasukan_setor);
        $parent['pengeluaran_servis'] = intval($this->pengeluaran_servis);
        $parent['pemasukan_setor_rp'] = rupiah($this->pemasukan_setor);
        $parent['pengeluaran_servis_rp'] = rupiah($this->pengeluaran_servis);

        return $parent;
    }
}
