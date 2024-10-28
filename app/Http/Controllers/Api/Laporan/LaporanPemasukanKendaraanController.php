<?php

namespace App\Http\Controllers\Api\Laporan;

use App\Http\Controllers\Controller;
use App\Http\Resources\Laporan\PemasukanKendaraanSendiriCollection;
use App\Http\Resources\Laporan\PemasukanKendaraanSubkonCollection;
use App\Services\LaporanPemasukanKendaraanService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanPemasukanKendaraanController extends Controller
{
    private $laporanPemasukanKendaraanService;

    public function __construct(LaporanPemasukanKendaraanService $laporanPemasukanKendaraanService)
    {
        $this->laporanPemasukanKendaraanService = $laporanPemasukanKendaraanService;
    }

    /**
     * @OA\Get(
     * path="/api/laporan/pemasukan-kendaraan-sendiri",
     * summary="Get data Laporan Pemasukan Kendaraan Sendiri",
     * tags={"Laporan"},
     * @OA\Parameter(
     * name="tanggal_awal",
     * description="Tanggal awal untuk mencari data Laporan Pemasukan Kendaraan Sendiri",
     * required=true,
     * in="query",
     * ),
     * @OA\Parameter(
     * name="tanggal_akhir",
     * description="Tanggal akhir untuk mencari data Laporan Pemasukan Kendaraan Sendiri",
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
     * description="Data Laporan Pemasukan Kendaraan Sendiri berhasil ditemukan"
     * ),
     * )
     */
    public function getLaporanPemasukanKendaraanSendiri(Request $request)
    {
        $data = $this->laporanPemasukanKendaraanService->getLaporanPemasukanKendaraanSendiri($request);
        return response()->json(
            [
                'status' => 'success',
                'data' => new PemasukanKendaraanSendiriCollection($data)
            ]
        );
    }

    /**
     * @OA\Get(
     * path="/api/laporan/pemasukan-kendaraan-subkon",
     * summary="Get data Laporan Pemasukan Kendaraan Subkon",
     * tags={"Laporan"},
     * @OA\Parameter(
     * name="tanggal_awal",
     * description="Tanggal awal untuk mencari data Laporan Pemasukan Kendaraan Subkon",
     * required=true,
     * in="query",
     * ),
     * @OA\Parameter(
     * name="tanggal_akhir",
     * description="Tanggal akhir untuk mencari data Laporan Pemasukan Kendaraan Subkon",
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
     * description="Data Laporan Pemasukan Kendaraan Subkon berhasil ditemukan"
     * ),
     * )
     */
    public function getLaporanPemasukanKendaraanSubkon(Request $request)
    {
        $data = $this->laporanPemasukanKendaraanService->getLaporanPemasukanKendaraanSubkon($request);
        return response()->json(
            [
                'status' => 'success',
                'data' => new PemasukanKendaraanSubkonCollection($data)
            ]
        );
    }

    public function generatePemasukanKendaraanSubkonPDF(Request $request)
    {
        $request->merge(['is_all' => true]);
        $result = $this->laporanPemasukanKendaraanService->getLaporanPemasukanKendaraanSubkon($request);
        Carbon::setLocale('id');
        $filename = 'Laporan Pemasukan Kendaraan Subkon Periode ' . Carbon::parse($request->tanggal_awal)->translatedFormat('j F Y') . ' - ' . Carbon::parse($request->tanggal_akhir)->translatedFormat('j F Y');
        $title = "Laporan Pemasukan Kendaraan Subkon";
        $data = [
            'filename' => $filename,
            'periode' => Carbon::parse($request->tanggal_awal)->translatedFormat('j F Y') . ' - ' . Carbon::parse($request->tanggal_akhir)->translatedFormat('j F Y'),
            'data' => (new PemasukanKendaraanSubkonCollection($result))->toArray($result),
            'title'=> $title
        ];
        $pdf = Pdf::setPaper('A4', 'portrait')->loadView('generate.pdf.pemasukan-kendaraan', $data);
        return $pdf->stream($filename .'.pdf');
    }
    public function generatePemasukanKendaraanSendiriPDF(Request $request)
    {
        $request->merge(['is_all' => true]);
        $result = $this->laporanPemasukanKendaraanService->getLaporanPemasukanKendaraanSendiri($request);
        Carbon::setLocale('id');
        $filename = 'Laporan Pemasukan Kendaraan Sendiri Periode ' . Carbon::parse($request->tanggal_awal)->translatedFormat('j F Y') . ' - ' . Carbon::parse($request->tanggal_akhir)->translatedFormat('j F Y');
        $title = "Laporan Pemasukan Kendaraan Sendiri";
        $data = [
            'filename' => $filename,
            'periode' => Carbon::parse($request->tanggal_awal)->translatedFormat('j F Y') . ' - ' . Carbon::parse($request->tanggal_akhir)->translatedFormat('j F Y'),
            'data' => (new PemasukanKendaraanSendiriCollection($result))->toArray($result),
            'title' => $title
        ];
        $pdf = Pdf::setPaper('A4', 'portrait')->loadView('generate.pdf.pemasukan-kendaraan', $data);
        return $pdf->stream($filename .'.pdf');
    }
}
