<?php

namespace App\Http\Resources\LaporanV2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ArmadaRugiLabaCollection extends ResourceCollection
{

  private $totalSetor;
  private $totalServis;
  private $totalAkhir;

  public function __construct($collection, $totalSetor, $totalServis, $totalAkhir)
  {
    parent::__construct($collection);
    $this->totalSetor = $totalSetor;
    $this->totalServis = $totalServis;
    $this->totalAkhir = $totalAkhir;
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
      'list' => ArmadaRugiLabaResource::collection($this->collection),
      'total' => [
        'pemasukan_setor' => $this->totalSetor,
        'pengeluaran_servis' => $this->totalServis,
        'total_akhir' => $this->totalAkhir,
      ],
      'meta' => [
        'links' => $this->getUrlRange(1, $this->lastPage()),
        'total' => $this->total(),
      ]
    ];
  }
}
