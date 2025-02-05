<?php

namespace App\Http\Controllers\Api\Laporan;

use App\DataTransferObjects\HutangCustomerParam;
use App\DataTransferObjects\HutangSopirParam;
use App\DataTransferObjects\HutangSubkonParam;
use App\DataTransferObjects\KasHarianParam;
use App\Helpers\Laporan\V2\KasHarian;
use App\Helpers\LaporanV2Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LaporanRequest\V2\HutangCustomerRequest;
use App\Http\Requests\LaporanRequest\V2\HutangSopirRequest;
use App\Http\Requests\LaporanRequest\V2\HutangSubkonRequest;
use App\Http\Resources\LaporanV2\HutangPiutangCustomerCollection;
use App\Http\Resources\LaporanV2\HutangPiutangCustomerResource;
use App\Http\Resources\LaporanV2\HutangPiutangSopirCollection;
use App\Http\Resources\LaporanV2\HutangPiutangSopirResource;
use App\Http\Resources\LaporanV2\HutangPiutangSubkonCollection;
use App\Http\Resources\LaporanV2\HutangPiutangSubkonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LaporanV2Controller extends Controller
{
    public function hutangSopir(HutangSopirRequest $request)
    {
        $sopirId = $request->get('sopirId');
        $tanggalAwal = $request->get('tanggalAwal');
        $tanggalAkhir = $request->get('tanggalAkhir');

        $export = boolval($request->get('export', false));

        try {
            $response = LaporanV2Helper::getHutangSopir(
                new HutangSopirParam($tanggalAwal, $tanggalAkhir, $sopirId),
                $export
            );

            if ($export) {
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadView('generate.pdf.v2.hutang-sopir', [
                    'filename' => 'Laporan Hutang Sopir',
                    'orders' => HutangPiutangSopirResource::collection($response['orders'])->toArray($request),
                    'sopir' => implode(", ", array_map(function ($item) {
                        return $item->sopir;
                    }, $response['items'])),
                    'tanggalAwal' => $tanggalAwal,
                    'tanggalAkhir' => $tanggalAkhir,
                ]);
                return $pdf->stream();
            }

            return response()->json([
                'status' => 'success',
                'data' => new HutangPiutangSopirCollection(
                    $response['orders'],
                    items: $response['items']
                ),
            ]);
        } catch (\Throwable $th) {
            if ($th instanceof HttpException) {
                return response()->json([
                    'message' => $th->getMessage(),
                ], $th->getStatusCode() ?? 500);
            }

            return response()->json([
                'message' => 'Terjadi kesalahan pada server ',
            ], 500);
        }
    }

    public function hutangPiutangCustomer(HutangCustomerRequest $request)
    {
        $penyewaId = $request->get('penyewaId');
        $subkon = $request->get('subkon');
        $status = $request->get('status');
        $tanggalAwal = $request->get('tanggalAwal');
        $tanggalAkhir = $request->get('tanggalAkhir');
        $export = boolval($request->get('export', false));

        try {
            $hutangCustomerParam = new HutangCustomerParam(
                $tanggalAwal,
                $tanggalAkhir,
                $subkon,
                $status,
                $penyewaId
            );

            $response = LaporanV2Helper::getHutangCustomer(
                $hutangCustomerParam,
                $export
            );

            if ($export) {
                $pdf = App::make('dompdf.wrapper');

                if ($response['customer']) {
                    $title = 'Laporan Hutang Pelanggan';
                } else {
                    $title = 'Laporan Hutang Seluruh Pelanggan';
                }

                $pdf->loadView('generate.pdf.v2.hutang-customer', [
                    'filename' => $title,
                    'orders' => HutangPiutangCustomerResource::collection($response['orders'])->toArray($request),
                    'customer' => $response['customer'],
                    'totalHutang' => $response['totalHutang'],
                    'totalHutangRange' => $response['totalHutangRange'],
                    'tanggalAwal' => $tanggalAwal,
                    'tanggalAkhir' => $tanggalAkhir,
                ]);
                return $pdf->stream();
            }

            return response()->json([
                'status' => 'success',
                'data' => new HutangPiutangCustomerCollection($response['orders'], $response['totalHutang']),
            ]);
        } catch (\Throwable $th) {
            if ($th instanceof HttpException) {
                return response()->json([
                    'message' => $th->getMessage(),
                ], $th->getStatusCode() ?? 500);
            }

            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
            ], 500);
        }
    }

    public function hutangPiutangSubkon(HutangSubkonRequest $request)
    {
        $subkonId = $request->get('subkonId');
        $tanggalAwal = $request->get('tanggalAwal');
        $tanggalAkhir = $request->get('tanggalAkhir');

        $export = boolval($request->get('export', false));

        try {
            $response = LaporanV2Helper::getHutangSubkon(
                new HutangSubkonParam($tanggalAwal, $tanggalAkhir, $subkonId),
                $export
            );

            if ($export) {
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadView('generate.pdf.v2.hutang-subkon', [
                    'filename' => 'Laporan Hutang Subkon',
                    'orders' => HutangPiutangSubkonResource::collection($response['orders'])->toArray($request),
                    'subkon' => $response['subkon'],
                    'totalHutangRange' => $response['totalHutangRange'],
                    'tanggalAwal' => $tanggalAwal,
                    'tanggalAkhir' => $tanggalAkhir,
                ]);
                return $pdf->stream();
            }

            return response()->json([
                'status' => 'success',
                'data' => new HutangPiutangSubkonCollection($response['orders'], $response['totalHutang'])
            ]);
        } catch (\Throwable $th) {
            if ($th instanceof HttpException) {
                return response()->json([
                    'message' => $th->getMessage(),
                ], $th->getStatusCode() ?? 500);
            }

            return response()->json([
                'message' => 'Terjadi kesalahan pada server' . $th->getMessage(),
            ], 500);
        }
    }

    public function kasHarian(Request $request)
    {
        try {
            $service = new KasHarian(
                param: new KasHarianParam(
                    tanggalAwal: $request->get('tanggalAwal'),
                    tanggalAkhir: $request->get('tanggalAkhir'),
                    rekeningId: $request->get('rekeningId'),
                    export: boolval($request->get('export', false))
                )
            );

            return $service->execute();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function thrSopir(Request $request)
    {
        $sopirId = $request->get('sopirId');
        $tahun = $request->get('tahun');

        try {
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
