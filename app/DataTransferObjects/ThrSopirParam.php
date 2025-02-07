<?php

namespace App\DataTransferObjects;

class ThrSopirParam
{
  public function __construct(
    public ?string $tanggalAwal,
    public ?string $tanggalAkhir,
    public ?int $sopirId,
    public ?bool $export
  ) {

    // Validate if parameter empty
    if (empty($sopirId) || empty($tanggalAwal) || empty($tanggalAkhir)) {
      throw new \Exception('Parameter sopirId, tanggalAwal, tanggalAkhir harus diisi');
    }

    // Validate if tanggalAwal <= tanggalAkhir
    if ($tanggalAwal > $tanggalAkhir) {
      throw new \Exception("Tanggal awal harus lebih kecil atau sama dengan tanggal akhir");
    }
  }
}
