<?php

namespace App\DataTransferObjects;

class HutangCustomerParam
{
  public function __construct(
    public string $tanggalAwal,
    public string $tanggalAkhir,
    public string $subkon,
    public string $status,
    public string|null $penyewaId,
  ) {}
}
