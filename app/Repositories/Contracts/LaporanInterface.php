<?php
  namespace App\Repositories\Contracts;

  interface LaporanInterface
  {
    public function getLaporanPemasukanCV($tanggal_awal,$tanggal_akhir);
    public function getLaporanPemasukanKendaraanSendiri($tanggal_awal,$tanggal_akhir,$m_armada_id);
    public function getLaporanPemasukanKendaraanSubkon($tanggal_awal,$tanggal_akhir);
  }