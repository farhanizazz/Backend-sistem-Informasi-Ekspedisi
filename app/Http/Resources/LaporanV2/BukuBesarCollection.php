<?php

namespace App\Http\Resources\LaporanV2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BukuBesarCollection extends ResourceCollection
{

  protected $rekening;
  protected $totalDebet;
  protected $totalKredit;
  protected $totalSaldo;

  public function __construct($collection, $rekening, $totalDebet, $totalKredit, $totalSaldo)
  {
    parent::__construct($collection);
    $this->rekening = $rekening;
    $this->totalDebet = $totalDebet;
    $this->totalKredit = $totalKredit;
    $this->totalSaldo = $totalSaldo;
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
      'rekening' => $this->rekening,
      'total_debet' => $this->totalDebet,
      'total_kredit' => $this->totalKredit,
      'total_saldo' => $this->totalSaldo,
    ];
  }
}
