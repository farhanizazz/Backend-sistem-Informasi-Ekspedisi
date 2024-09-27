<?php
namespace App\Services;

use App\Repositories\Contracts\LaporanInterface;
use Illuminate\Http\Request;

class LaporanPengeluaranService
{
  private $laporanPengeluaran;

  public function __construct(LaporanInterface $laporanPengeluaran)
  {
    $this->laporanPengeluaran = $laporanPengeluaran;
  }

  public function getLaporanPengeluaranServis(Request $request)
  {
    $tanggal_awal = $request->tanggal_awal;
    $tanggal_akhir = $request->tanggal_akhir;
    $itemPerPage = $request->itemPerPage;
    return $this->laporanPengeluaran->getLaporanPengeluaranServis($tanggal_awal,$tanggal_akhir,$itemPerPage);
  }
  
  public function getLaporanPengeluaranLain(Request $request)
  {
    $tanggal_awal = $request->tanggal_awal;
    $tanggal_akhir = $request->tanggal_akhir;
    $itemPerPage = $request->itemPerPage;
    return $this->laporanPengeluaran->getLaporanPengeluaranLain($tanggal_awal,$tanggal_akhir,$itemPerPage);
  }

  public function getLaporanPengeluaranSemua(Request $request)
  {
    $tanggal_awal = $request->tanggal_awal;
    $tanggal_akhir = $request->tanggal_akhir;
    $itemPerPage = $request->itemPerPage;
    return $this->laporanPengeluaran->getLaporanPengeluaranServis($tanggal_awal,$tanggal_akhir,$itemPerPage);
  }
}