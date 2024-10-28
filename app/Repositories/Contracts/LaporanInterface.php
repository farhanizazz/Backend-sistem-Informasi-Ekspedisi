<?php
  namespace App\Repositories\Contracts;

  interface LaporanInterface
  {
    public function getLaporanPemasukanCV($tanggal_awal,$tanggal_akhir,array $m_armada_id,$itemPerPage);
    public function getLaporanPemasukanCVAll($tanggal_awal,$tanggal_akhir,array $m_armada_id);
    public function getLaporanPemasukanKendaraanSendiri($tanggal_awal,$tanggal_akhir,array $m_armada_id,$itemPerPage,$all);
    public function getLaporanPemasukanKendaraanSubkon($tanggal_awal,$tanggal_akhir,array $m_armada_id,$itemPerPage,$all);
    public function getLaporanPengeluaranServis($tanggal_awal,$tanggal_akhir,array $m_armada_id,$itemPerPage,$all);
    public function getLaporanPengeluaranLain($tanggal_awal,$tanggal_akhir,array $m_armada_id,$itemPerPage,$all);
    public function getLaporanPengeluaranSemua($tanggal_awal,$tanggal_akhir,array $m_armada_id,$itemPerPage,$all);
  }