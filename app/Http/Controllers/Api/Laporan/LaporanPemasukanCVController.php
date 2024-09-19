<?php

namespace App\Http\Controllers\Api\Laporan;

use App\Http\Controllers\Controller;
use App\Http\Resources\Laporan\PemasukanCVCollection;
use App\Services\LaporanPemasukanCVService;
use Illuminate\Http\Request;

class LaporanPemasukanCVController extends Controller
{
    protected $laporanPemasukanCVService;

    public function __construct(LaporanPemasukanCVService $laporanPemasukanCVService)
    {
        $this->laporanPemasukanCVService = $laporanPemasukanCVService;
    }

    /**
     * @OA\Get(
     * path="/api/laporan/pemasukan-cv",
     * summary="Get data Laporan Pemasukan CV",
     * tags={"Laporan"},
     * @OA\Parameter(
     * name="tanggal_awal",
     * description="Tanggal awal untuk mencari data Laporan Pemasukan CV",
     * required=true,
     * in="query",
     * ),
     * @OA\Parameter(
     * name="tanggal_akhir",
     * description="Tanggal akhir untuk mencari data Laporan Pemasukan CV",
     * required=true,
     * in="query",
     * ),
     * @OA\Response(
     * response=200,
     * description="Data Laporan Pemasukan CV berhasil ditemukan"
     * ),
     * )
     */
    public function getLaporanPemasukanCV(Request $request)
    {
        $data = $this->laporanPemasukanCVService->getLaporanPemasukanCV($request);
        return response()->json(
            [
                'status' => 'success',
                'data' => new PemasukanCVCollection($data)
            ]
        );
    }
}
