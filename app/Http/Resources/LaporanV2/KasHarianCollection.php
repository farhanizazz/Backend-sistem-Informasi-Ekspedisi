<?php

namespace App\Http\Resources\LaporanV2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class KasHarianCollection extends ResourceCollection
{
  /**
   * Transform the resource collection into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    return [
      'list' => KasHarianResource::collection($this->collection),
      'meta' => [
        'links' => $this->getUrlRange(1, $this->lastPage()),
        'total' => $this->total(),
      ]
    ];
  }
}
