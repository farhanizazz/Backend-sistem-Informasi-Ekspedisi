<?php

namespace App\Http\Resources\LaporanV2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BukuBesarCollection extends ResourceCollection
{

  public function __construct($collection)
  {
    parent::__construct($collection);
  }

  /**
   * Transform the resource collection into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    return [
      'list' => BukuBesarResource::collection($this->collection),
    ];
  }
}
