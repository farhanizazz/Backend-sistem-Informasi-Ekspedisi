<?php
  namespace App\Repositories\Contracts;

  interface LaporanInterface
  {
    public function getLaporanPemasukanCV($tanggal_awal,$tanggal_akhir,$itemPerPage);
    public function getLaporanPemasukanKendaraanSendiri($tanggal_awal,$tanggal_akhir,$m_armada_id,$itemPerPage);
    public function getLaporanPemasukanKendaraanSubkon($tanggal_awal,$tanggal_akhir,$itemPerPage);
    public function getLaporanPengeluaranServis($tanggal_awal,$tanggal_akhir,$itemPerPage);
    public function getLaporanPengeluaranLain($tanggal_awal,$tanggal_akhir,$itemPerPage);
    public function getLaporanPengeluaranSemua($tanggal_awal,$tanggal_akhir,$itemPerPage);
  }