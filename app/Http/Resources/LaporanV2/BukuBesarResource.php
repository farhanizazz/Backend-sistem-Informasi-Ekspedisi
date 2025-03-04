<?php

namespace App\Http\Resources\LaporanV2;

use Illuminate\Http\Resources\Json\JsonResource;

class BukuBesarResource extends JsonResource
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
        return $parent;
    }
}
