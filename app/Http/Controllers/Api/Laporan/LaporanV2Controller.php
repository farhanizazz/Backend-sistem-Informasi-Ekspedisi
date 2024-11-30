<?php

namespace App\Http\Controllers\Api\Laporan;

use App\Http\Controllers\Controller;
use App\Http\Resources\LaporanV2\HutangPiutangCustomerCollection;
use App\Http\Resources\LaporanV2\HutangPiutangSopirCollection;
use App\Http\Resources\LaporanV2\HutangPiutangSubkonCollection;
use App\Models\Master\SopirModel;
use App\Models\Master\SubkonModel;
use App\Models\Transaksi\OrderModel;
use Illuminate\Http\Request;

class LaporanV2Controller extends Controller
{
    public function hutangSopir(Request $request)
    {
        $sopirId = $request->get('sopirId');
        $tanggalAwal = $request->get('tanggalAwal');
        $tanggalAkhir = $request->get('tanggalAkhir');

        if (!$tanggalAwal || !$tanggalAkhir) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tanggal awal and tanggal akhir are required'
            ], 400);
        }

        $sopir = SopirModel::where('id', $sopirId)->first();
        if (!$sopir) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sopir not found'
            ], 404);
        }

        $orderQuery = OrderModel::where('m_sopir_id', '=', $sopirId)
            ->whereBetween('tanggal_awal', [$tanggalAwal, $tanggalAkhir]);
        $orders = $orderQuery->paginate();

        $totalSisaUangJalan = OrderModel::where('m_sopir_id', '=', $sopirId)
            ->whereBetween('tanggal_awal', [$tanggalAwal, $tanggalAkhir])
            ->withSum('mutasi_jalan as total_pembayaran', 'nominal')
            ->get()
            ->sum(fn($order) => $order->uang_jalan_bersih - $order->total_pembayaran);
        $totalHutang = OrderModel::where('m_sopir_id', '=', $sopirId)
            ->whereBetween('tanggal_awal', [$tanggalAwal, $tanggalAkhir])
            ->withSum('mutasi_jalan as total_pembayaran', 'nominal')
            ->get()
            ->sum(function ($order) {
                $value = $order->uang_jalan_bersih - $order->total_pembayaran;
                return $value > 0 ? 0 : $value;
            });

        return response()->json([
            'status' => 'success',
            'data' => new HutangPiutangSopirCollection(
                $orders,
                $totalSisaUangJalan,
                $totalHutang,
                $sopir
            ),
        ]);
    }


    public function hutangPiutangCustomer(Request $request)
    {
        $penyewaId = $request->get('penyewaId');
        $subkon = $request->get('subkon');
        $status = $request->get('status');
        $tanggalAwal = $request->get('tanggalAwal');
        $tanggalAkhir = $request->get('tanggalAkhir');

        if (!$subkon || !$status || !$tanggalAwal || !$tanggalAkhir || !$penyewaId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Subkon, status, tanggal awal, tanggal akhir, and penyewa ID are required'
            ], 400);
        }

        $orders = OrderModel::query()->where('m_penyewa_id', $penyewaId);

        if ($subkon !== 'all') {
            $orders->where('m_subkon_id', '=', $subkon);
        }

        if ($status !== 'all') {
            $orders->where('status', '=', $status);
        }

        $cloneOrderQuery = clone $orders;
        $orders->whereBetween('tanggal_awal', [$tanggalAwal, $tanggalAkhir]);
        $orders = $orders->paginate();

        $totalHutang = $cloneOrderQuery
            ->withSum('mutasi_order as total_pembayaran', 'nominal')
            ->get()
            ->sum(fn($order) => $order->harga_order_bersih - $order->total_pembayaran);

        return response()->json([
            'status' => 'success',
            'data' => new HutangPiutangCustomerCollection($orders, $totalHutang),
        ]);
    }

    public function hutangPiutangSubkon(Request $request)
    {
        $subkonId = $request->get('subkonId');
        $tanggalAwal = $request->get('tanggalAwal');
        $tanggalAkhir = $request->get('tanggalAkhir');

        if (!$subkonId || !$tanggalAwal || !$tanggalAkhir) {
            return response()->json([
                'status' => 'error',
                'message' => 'Subkon ID, tanggal awal, and tanggal akhir are required'
            ], 400);
        }

        $subkon = SubkonModel::find($subkonId);

        if (!$subkon) {
            return response()->json([
                'status' => 'error',
                'message' => 'Subkon not found'
            ], 404);
        }

        $orderQuery = OrderModel::query()
            ->where('m_subkon_id', $subkonId)
            ->whereBetween('tanggal_awal', [$tanggalAwal, $tanggalAkhir]);
        $orders = $orderQuery->paginate();
        $totalHutang = OrderModel::query()
            ->where('m_subkon_id', $subkonId)
            ->whereBetween('tanggal_awal', [$tanggalAwal, $tanggalAkhir])
            ->withSum('mutasi_jual as total_pembayaran', 'nominal')
            ->get()
            ->sum(fn($order) => $order->harga_jual_bersih - $order->total_pembayaran);
        return response()->json([
            'status' => 'success',
            'data' => new HutangPiutangSubkonCollection($orders, $totalHutang)
        ]);
    }
}
