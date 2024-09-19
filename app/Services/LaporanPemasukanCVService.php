<?php
  namespace App\Services;

use App\Repositories\Contracts\LaporanInterface;
use Illuminate\Http\Request;

  class LaporanPemasukanCVService
  {
    protected $laporanPemasukan;
  
    public function __construct(LaporanInterface $laporanPemasukan)
    {
      $this->laporanPemasukan = $laporanPemasukan;
    }
  
    public function getLaporanPemasukanCV(Request $request)
    {
      $tanggal_awal = $request->tanggal_awal;
      $tanggal_akhir = $request->tanggal_akhir;
      return $this->laporanPemasukan->getLaporanPemasukanCV($tanggal_awal,$tanggal_akhir);
    }
  }