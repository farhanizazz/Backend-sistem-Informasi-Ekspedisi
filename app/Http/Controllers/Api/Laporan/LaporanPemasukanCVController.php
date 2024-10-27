<?php

namespace App\Http\Controllers\Api\Laporan;

use App\Http\Controllers\Controller;
use App\Http\Resources\Laporan\PemasukanCVALL;
use App\Http\Resources\Laporan\PemasukanCVAllCollection;
use App\Http\Resources\Laporan\PemasukanCVAllResource;
use App\Http\Resources\Laporan\PemasukanCVCollection;
use App\Services\LaporanPemasukanCVService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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
     * @OA\Parameter(
     * name="itemPerPage",
     * description="Jumlah data per halaman",
     * required=false,
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

    public function generatePemasukanCVPDF(Request $request)
    {
        $result = $this->laporanPemasukanCVService->getLaporanPemasukanCVAll($request);

        Carbon::setLocale('id');
        $filename = 'Laporan Pemasukan CV Periode ' . Carbon::parse($request->tanggal_awal)->translatedFormat('j F Y') . ' - ' . Carbon::parse($request->tanggal_akhir)->translatedFormat('j F Y');
        $data = [
            'filename' => $filename,
            'data' => (new PemasukanCVCollection($result))->toArray($result),
        ];
        $pdf = Pdf::setPaper('A4', 'portrait')->loadView('generate.pdf.pemasukan-cv', $data);
        return $pdf->stream($filename .'.pdf');
    }


}
