<?php

namespace App\Http\Controllers\Api\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Master\SopirModel;
use App\Models\Master\SubkonModel;
use App\Models\Master\TambahanModel;
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

        $orders = OrderModel::where('m_sopir_id', '=', $sopirId)
            ->whereBetween('tanggal_awal', [$tanggalAwal, $tanggalAkhir])
            ->get();

        $detail = [];
        $totalSisaUangJalan = 0;
        $totalHutang = 0;
        foreach ($orders as $order) {
            $totalBiayaTambahan = 0;
            $totalBiayaKurang = 0;

            foreach ($order->biayaLainUangJalanArr as $biayaLainUangJalan) {
                if ($biayaLainUangJalan['sifat'] == TambahanModel::SIFAT_MENAMBAHKAN) {
                    $totalBiayaTambahan += $biayaLainUangJalan['nominal'];
                } else if ($biayaLainUangJalan['sifat'] == TambahanModel::SIFAT_MENGURANGI) {
                    $totalBiayaKurang += $biayaLainUangJalan['nominal'];
                }
            }

            $totalUangJalan = $totalBiayaTambahan + $totalBiayaKurang + $order->uang_jalan - $order->potongan_wajib;
            $listRincianUangJalan = $order->mutasi_jalan()->get();
            $rincian = [];
            $jumlahPembayaran = 0;
            foreach ($listRincianUangJalan as $rincianUangJalan) {
                $rincian[] = [
                    'tanggal' => $rincianUangJalan->tanggal_pembayaran,
                    'keterangan' => $rincianUangJalan->keterangan,
                    'nominal' => $rincianUangJalan->nominal,
                ];

                // Calculate the remaining amount
                $jumlahPembayaran += $rincianUangJalan->nominal;
            }

            $schema = [
                'tanggal' => $order->tanggal_awal,
                'no_transaksi' => $order->no_transaksi,
                'penyewa' => $order->penyewa->nama_perusahaan,
                'muatan' => $order->muatan,
                'asal' => $order->asal,
                'tujuan' => $order->tujuan,

                'uang_jalan' => $order->uang_jalan,
                'biaya_tambahan' => $totalBiayaTambahan,
                'biaya_kurang' => $totalBiayaKurang,
                'pot_thr' => $order->potongan_wajib,
                'total_uang_jalan' => $totalUangJalan,

                'rincian' => $rincian,

                'jumlah_pembayaran' => $jumlahPembayaran,
                'sisa_uang_jalan' => $totalUangJalan - $jumlahPembayaran
            ];

            $detail[] = $schema;
            $totalSisaUangJalan += $schema['sisa_uang_jalan'];

            if ($schema['sisa_uang_jalan'] < 0) {
                $totalHutang += abs($schema['sisa_uang_jalan']);
            }
        }

        $schemas = [
            'nama_sopir' => $sopir->nama,
            'total_hutang' => $totalHutang,
            'tanggal' => [
                'start' => $tanggalAwal,
                'end' => $tanggalAkhir
            ],
            'total_sisa_uang_jalan' => $totalSisaUangJalan,
            'detail' => $detail
        ];

        return response()->json([
            'success' => true,
            'schema' => $schemas,
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

        $orders->whereBetween('tanggal_awal', [$tanggalAwal, $tanggalAkhir]);

        $orders = $orders->get();

        $detail = [];
        $totalSisaTagihan = 0;
        foreach ($orders as $index => $order) {
            $listRincianOrder = $order->mutasi_order()->get();
            $rincian = [];
            $jumlahPembayaran = 0;
            foreach ($listRincianOrder as $rincianOrder) {
                $rincian[] = [
                    'tanggal' => $rincianOrder->tanggal_pembayaran,
                    'keterangan' => $rincianOrder->keterangan,
                    'nominal' => $rincianOrder->nominal,
                ];

                // Calculate the remaining amount
                $jumlahPembayaran += $rincianOrder->nominal;
            }
            $schema = [
                'no' => $index + 1,
                'tanggal' => $order->tanggal_awal,
                'no_transaksi' => $order->no_transaksi,
                'nopol' => $order->armada ? $order->armada->nopol : $order->nopol_subkon,
                'sopir' => $order->sopir ? $order->sopir->nama : $order->sopir_subkon,
                'penyewa' => $order->penyewa->nama_perusahaan,
                'muatan' => $order->muatan,
                'asal' => $order->asal,
                'tujuan' => $order->tujuan,
                'rincian' => $rincian,
                'harga_order' => $order->harga_order_bersih,
                'biaya_tambah_kurang' => $jumlahPembayaran,
                'pph' => $order->total_pajak,
                'sisa_tagihan' => $order->harga_order_bersih - $jumlahPembayaran
            ];

            $detail[] = $schema;
            $totalSisaTagihan += $schema['sisa_tagihan'];
        }

        $schemas = [
            'tanggal' => [
                'start' => $tanggalAwal,
                'end' => $tanggalAkhir
            ],
            'detail' => $detail,
            'total_hutang' => $totalSisaTagihan,
        ];

        return response()->json([
            'success' => true,
            'schema' => $schemas,
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

        $orders = OrderModel::query()->where('m_subkon_id', $subkonId)->whereBetween('tanggal_awal', [$tanggalAwal, $tanggalAkhir]);
        $orders = $orders->get();
        $detail = [];
        foreach ($orders as $index => $order) {

            $jumlahPembayaran = 0;
            $listRincianJual = $order->mutasi_jual()->get();
            $rincian = [];
            foreach ($listRincianJual as $rincianJual) {
                $rincian[] = [
                    'tanggal' => $rincianJual->tanggal_pembayaran,
                    'keterangan' => $rincianJual->keterangan,
                    'nominal' => $rincianJual->nominal,
                ];

                // Calculate the remaining amount
                $jumlahPembayaran += $rincianJual->nominal;
            }
            $schema = [
                'id' => $index + 1,
                'tanggal' => $order->tanggal_awal,
                'no_transaksi' => $order->no_transaksi,
                'nopol' => $order->nopol_subkon,
                'sopir' => $order->sopir_subkon,
                'penyewa' => $order->penyewa->nama_perusahaan,
                'muatan' => $order->muatan,
                'asal' => $order->asal,
                'tujuan' => $order->tujuan,
                'harga_order' => $order->harga_order_bersih,
                'harga_jual' => $order->harga_jual_bersih,
                'rincian' => $rincian,
                // !biaya tambah_kurang apa ini ?
                'biaya_tambah_kurang' => $jumlahPembayaran,
                'pph' => $order->total_pajak,
                'sisa_hutang' => $order->harga_jual_bersih - $jumlahPembayaran
            ];

            $detail[] = $schema;
        }

        $schemas = [
            'tanggal' => [
                'start' => $tanggalAwal,
                'end' => $tanggalAkhir
            ],
            'detail' => $detail,
            'total_hutang' => array_sum(array_column($detail, 'sisa_hutang')),
        ];

        return response()->json([
            'success' => true,
            'schema' => $schemas,
        ]);
    }
}
