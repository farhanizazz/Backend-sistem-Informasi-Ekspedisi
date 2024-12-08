<?php

namespace App\DataTransferObjects;

class HutangSubkonParam
{
  public function __construct(
    public string $tanggalAwal,
    public string $tanggalAkhir,
    public string $subkon,
  ) {}
}
