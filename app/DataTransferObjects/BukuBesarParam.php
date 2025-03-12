<?php

namespace App\DataTransferObjects;

class BukuBesarParam
{
  public function __construct(
    public ?string $tanggalAwal,
    public ?string $tanggalAkhir,
    public ?string $rekeningId,
    public ?bool $export
  ) {

    // Validate if parameter empty
    if (empty($rekeningId)) {
      throw new \Exception('Parameter rekeningId harus diisi');
    }


    if (empty($tanggalAwal) || empty($tanggalAkhir)) {
      throw new \Exception('Parameter tanggalAwal, tanggalAkhir harus diisi');
    }

    if (!empty($tanggalAwal) || !empty($tanggalAkhir)) {

      // Validate if tanggalAwal <= tanggalAkhir
      if ($tanggalAwal > $tanggalAkhir) {
        throw new \Exception("Tanggal awal harus lebih kecil atau sama dengan tanggal akhir");
      }
    }
  }
}
