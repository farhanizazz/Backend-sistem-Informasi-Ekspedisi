<?php

namespace App\Http\Controllers\Api\Laporan;

use App\Http\Controllers\Controller;
use App\Http\Resources\Laporan\PengeluaranServisCollection;
use App\Services\LaporanPengeluaranService;
use Illuminate\Http\Request;

class LaporanPengeluaranController extends Controller
{
    private $laporanPengeluaranService;

    public function __construct(LaporanPengeluaranService $laporanPengeluaranService)
    {
        $this->laporanPengeluaranService = $laporanPengeluaranService;
    }

    /**
     * @OA\Get(
     * path="/api/laporan/pengeluaran-servis",
     * summary="Get data Laporan Pengeluaran Servis",
     * tags={"Laporan"},
     * @OA\Parameter(
     * name="tanggal_awal",
     * description="Tanggal awal untuk mencari data Laporan Pengeluaran Servis",
     * required=true,
     * in="query",
     * ),
     * @OA\Parameter(
     * name="tanggal_akhir",
     * description="Tanggal akhir untuk mencari data Laporan Pengeluaran Servis",
     * required=true,
     * in="query",
     * ),
     * @OA\Parameter(
     * name="itemPerPage",
     * description="Jumlah data per halaman",
     * required=false,
     * in="query",
     * ),
     * @OA\Response(
     * response=200,
     * description="Data Laporan Pengeluaran Servis berhasil ditemukan"
     * ),
     * )
     */
    public function getLaporanPengeluaranServis(Request $request)
    {
        $data = $this->laporanPengeluaranService->getLaporanPengeluaranServis($request);
        return response()->json(
            [
                'status' => 'success',
                'data' => new PengeluaranServisCollection($data)
            ]
        );
    }

    /**
     * @OA\Get(
     * path="/api/laporan/pengeluaran-lain",
     * summary="Get data Laporan Pengeluaran Lain",
     * tags={"Laporan"},
     * @OA\Parameter(
     * name="tanggal_awal",
     * description="Tanggal awal untuk mencari data Laporan Pengeluaran Lain",
     * required=true,
     * in="query",
     * ),
     * @OA\Parameter(
     * name="tanggal_akhir",
     * description="Tanggal akhir untuk mencari data Laporan Pengeluaran Lain",
     * required=true,
     * in="query",
     * ),
     * @OA\Parameter(
     * name="itemPerPage",
     * description="Jumlah data per halaman",
     * required=false,
     * in="query",
     * ),
     * @OA\Response(
     * response=200,
     * description="Data Laporan Pengeluaran Lain berhasil ditemukan"
     * ),
     * )
     */
    public function getLaporanPengeluaranLain(Request $request)
    {
        $data = $this->laporanPengeluaranService->getLaporanPengeluaranLain($request);
        return response()->json(
            [
                'status' => 'success',
                'data' => new PengeluaranServisCollection($data)
            ]
        );
    }

    /**
     * @OA\Get(
     * path="/api/laporan/pengeluaran-semua",
     * summary="Get data Laporan Pengeluaran Semua",
     * tags={"Laporan"},
     * @OA\Parameter(
     * name="tanggal_awal",
     * description="Tanggal awal untuk mencari data Laporan Pengeluaran Semua",
     * required=true,
     * in="query",
     * ),
     * @OA\Parameter(
     * name="tanggal_akhir",
     * description="Tanggal akhir untuk mencari data Laporan Pengeluaran Semua",
     * required=true,
     * in="query",
     * ),
     * @OA\Parameter(
     * name="itemPerPage",
     * description="Jumlah data per halaman",
     * required=false,
     * in="query",
     * ),
     * @OA\Response(
     * response=200,
     * description="Data Laporan Pengeluaran Semua berhasil ditemukan"
     * ),
     * )
     */
    public function getLaporanPengeluaranSemua(Request $request)
    {
        $data = $this->laporanPengeluaranService->getLaporanPengeluaranSemua($request);
        return response()->json(
            [
                'status' => 'success',
                'data' => new PengeluaranServisCollection($data)
            ]
        );
    }
    
}
