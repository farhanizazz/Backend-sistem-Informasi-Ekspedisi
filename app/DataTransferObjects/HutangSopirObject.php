<?php

namespace App\DataTransferObjects;

class HutangSopirObject
{
  public function __construct(
    public ?string $sopir,
    public float $totalSisaUangJalan,
    public float $totalHutang
  ) {}
}
