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
