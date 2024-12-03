<?php

namespace App\Http\Controllers\Api\Laporan;

use App\DataTransferObjects\HutangSopirObject;
use App\Enums\TipeKalkulasiSisaUangJalanEnum;
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

        $items = [];
        $sopirs = [];

        if ($sopirId) {
            // Ambil data per sopir berdasarkan id yang valid
            $sopirIds = explode(',', $sopirId);
            $sopirs = SopirModel::whereIn('id', $sopirIds)->get();

            // Kalkulasi data per sopir
            foreach ($sopirs as $index => $sopir) {
                $orderQuery = OrderModel::where('m_sopir_id', $sopir->id);

                $totalSisaUangJalan = OrderModel::kalkulasSisaUangJalan($orderQuery);
                $totalHutang = OrderModel::kalkulasSisaUangJalan($orderQuery, TipeKalkulasiSisaUangJalanEnum::HUTANG);

                $items[$index] = new HutangSopirObject(
                    $sopir->nama,
                    $totalSisaUangJalan,
                    $totalHutang
                );
            }

            // Dapatkan paginasi dari semua item transaksi
            $orders = OrderModel::whereBetween('tanggal_awal', [$tanggalAwal, $tanggalAkhir])
                ->whereIn('m_sopir_id', $sopirIds)
                ->paginate();
        } else {
            // Kalkulasi data per sopir
            $orderQuery = OrderModel::whereNotNull('m_sopir_id');
            $totalSisaUangJalan = OrderModel::kalkulasSisaUangJalan($orderQuery);
            $totalHutang = OrderModel::kalkulasSisaUangJalan($orderQuery, TipeKalkulasiSisaUangJalanEnum::HUTANG);
            $items[0] = new HutangSopirObject(
                'Semua Sopir',
                $totalSisaUangJalan,
                $totalHutang
            );

            // Dapatkan paginasi dari semua item transaksi
            $orders = $orderQuery
                ->whereBetween('tanggal_awal', [$tanggalAwal, $tanggalAkhir])
                ->paginate();
        }

        return response()->json([
            'status' => 'success',
            'data' => new HutangPiutangSopirCollection(
                $orders,
                $items
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

        $orders = OrderModel::query()
            ->where('m_subkon_id', $subkonId)
            ->whereBetween('tanggal_awal', [$tanggalAwal, $tanggalAkhir])
            ->paginate();
        $totalHutang = OrderModel::query()
            ->where('m_subkon_id', $subkonId)
            // ->whereBetween('tanggal_awal', [$tanggalAwal, $tanggalAkhir])
            ->withSum('mutasi_jual as total_pembayaran', 'nominal')
            ->get()
            ->sum(fn($order) => $order->harga_jual_bersih - $order->total_pembayaran);
        return response()->json([
            'status' => 'success',
            'data' => new HutangPiutangSubkonCollection($orders, $totalHutang)
        ]);
    }
}
