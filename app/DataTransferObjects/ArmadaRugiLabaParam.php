<?php

namespace App\DataTransferObjects;

class ArmadaRugiLabaParam
{
  public function __construct(
    public ?string $tanggalAwal,
    public ?string $tanggalAkhir,
    public ?string $armadaId,
    public ?bool $export
  ) {

    // Validate if parameter empty
    if (empty($armadaId)) {
      // throw new \Exception('Parameter armadaId harus diisi');
      $this->armadaId = 'all';
    }

    if (!empty($tanggalAwal) || !empty($tanggalAkhir)) {
      if (empty($tanggalAwal) || empty($tanggalAkhir)) {
        throw new \Exception('Parameter tanggalAwal, tanggalAkhir harus diisi');
      }

      // Validate if tanggalAwal <= tanggalAkhir
      if ($tanggalAwal > $tanggalAkhir) {
        throw new \Exception("Tanggal awal harus lebih kecil atau sama dengan tanggal akhir");
      }
    }
  }
}
