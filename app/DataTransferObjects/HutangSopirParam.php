<?php

namespace App\DataTransferObjects;

class HutangSopirParam
{
  public function __construct(
    public string $tanggalAwal,
    public string $tanggalAkhir,
    public ?string $sopirId,
  ) {}
}
