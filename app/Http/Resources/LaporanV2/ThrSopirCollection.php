<?php

namespace App\Http\Resources\LaporanV2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ThrSopirCollection extends ResourceCollection
{
  private $totalThr;

  public function __construct($collection, $totalThr)
  {
    parent::__construct($collection);
    $this->totalThr = $totalThr;
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
      'list' => ThrSopirResource::collection($this->collection),
      'meta' => [
        'links' => $this->getUrlRange(1, $this->lastPage()),
        'total' => $this->totalThr
      ]
    ];
  }
}
