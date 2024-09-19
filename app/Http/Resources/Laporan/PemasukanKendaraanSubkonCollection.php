<?php

namespace App\Http\Resources\Laporan;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PemasukanKendaraanSubkonCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
