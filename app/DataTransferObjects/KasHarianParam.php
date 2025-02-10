<?php

namespace App\DataTransferObjects;

class KasHarianParam
{
  public function __construct(
    public ?string $tanggalAwal,
    public ?string $tanggalAkhir,
    public ?int $rekeningId,
    public ?bool $export
  ) {

    // Validate if parameter empty
    if (empty($rekeningId) || empty($tanggalAwal) || empty($tanggalAkhir)) {
      throw new \Exception('Parameter rekeningId, tanggalAwal, tanggalAkhir harus diisi');
    }

    // Validate if tanggalAwal <= tanggalAkhir
    if ($tanggalAwal > $tanggalAkhir) {
      throw new \Exception("Tanggal awal harus lebih kecil atau sama dengan tanggal akhir");
    }
  }
}
