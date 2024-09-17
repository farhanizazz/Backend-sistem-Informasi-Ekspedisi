<?php
  namespace App\Services;
  
use App\Repositories\Contracts\LaporanPemasukanCVInterface;
use Illuminate\Http\Request;

  class LaporanPemasukanCVService
  {
    protected $laporanPemasukanCVRepository;
  
    public function __construct(LaporanPemasukanCVInterface $laporanPemasukanCVRepository)
    {
      $this->laporanPemasukanCVRepository = $laporanPemasukanCVRepository;
    }
  
    public function getLaporanPemasukanCV(Request $request)
    {
      $tanggal_awal = $request->tanggal_awal;
      $tanggal_akhir = $request->tanggal_akhir;
      return $this->laporanPemasukanCVRepository->getLaporanPemasukanCV($tanggal_awal,$tanggal_akhir);
    }
  }