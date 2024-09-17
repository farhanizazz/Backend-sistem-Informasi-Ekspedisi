<?php
  namespace App\Repositories\Contracts;

  interface LaporanPemasukanCVInterface
  {
    public function getLaporanPemasukanCV($tanggal_awal,$tanggal_akhir);
  }