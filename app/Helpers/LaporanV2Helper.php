<?php

namespace App\Helpers;

use App\DataTransferObjects\HutangCustomerParam;
use App\DataTransferObjects\HutangSopirObject;
use App\DataTransferObjects\HutangSopirParam;
use App\DataTransferObjects\HutangSubkonParam;
use App\Enums\TipeKalkulasiSisaUangJalanEnum;
use App\Models\Master\PenyewaModel;
use App\Models\Master\SopirModel;
use App\Models\Master\SubkonModel;
use App\Models\Transaksi\OrderModel;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LaporanV2Helper
{

  public static function getHutangSopir(HutangSopirParam $params, bool $export = false)
  {
    $items = [];
    $sopirs = [];

    if ($params->sopirId) {
      // Ambil data per sopir berdasarkan id yang valid
      $sopirIds = explode(',', $params->sopirId);
      $sopirs = SopirModel::whereIn('id', $sopirIds)->with('hutangs')->get();

      // Kalkulasi data per sopir
      foreach ($sopirs as $index => $sopir) {
        $orderQuery = OrderModel::where('m_sopir_id', $sopir->id);
             
        $totalSisaUangJalan = OrderModel::kalkulasSisaUangJalan($orderQuery);
        // $totalHutang = OrderModel::kalkulasSisaUangJalan($orderQuery, TipeKalkulasiSisaUangJalanEnum::HUTANG);
        $totalHutang = $sopir->hutangs()->sum('nominal_trans');
        $customOrderQuery = $orderQuery->whereBetween('tanggal_awal', [$params->tanggalAwal, $params->tanggalAkhir]);
        $sisaUangJalanRange = OrderModel::kalkulasSisaUangJalan($customOrderQuery, TipeKalkulasiSisaUangJalanEnum::ALL);

        $items[$index] = new HutangSopirObject(
          $sopir->nama,
          $totalSisaUangJalan,
          $totalHutang,
          $sisaUangJalanRange
        );
      }

      // Dapatkan paginasi dari semua item transaksi
      $orders = OrderModel::whereBetween('tanggal_awal', [$params->tanggalAwal, $params->tanggalAkhir])
        ->whereIn('m_sopir_id', $sopirIds);
    } else {
      // Kalkulasi data per sopir
      $orderQuery = OrderModel::whereNotNull('m_sopir_id');
      // $totalSisaUangJalan = OrderModel::kalkulasSisaUangJalan($orderQuery);
      // $totalHutang = OrderModel::kalkulasSisaUangJalan($orderQuery, TipeKalkulasiSisaUangJalanEnum::HUTANG);
      $items[0] = new HutangSopirObject(
        'Semua Sopir',
        0,
        0
      );

      // Dapatkan paginasi dari semua item transaksi
      $orders = $orderQuery
        ->whereBetween('tanggal_awal', [$params->tanggalAwal, $params->tanggalAkhir]);
    }

    // If for export, get all items
    if ($export) {
      $orders = $orders->get();
    } else {
      $orders = $orders->paginate();
    }

    return [
      'items' => $items,
      'orders' => $orders
    ];
  }


  public static function getHutangCustomer(HutangCustomerParam $param, bool $export): array
  {
    if ($param->penyewaId != null) {
      $penyewa = PenyewaModel::find($param->penyewaId);
      if (!$penyewa) {
        throw new HttpException(404, 'Penyewa not found');
      }

      $query = OrderModel::query()->where('m_penyewa_id', $penyewa->id);
    } else {
      $query = OrderModel::query();
    }

    $orders = $query
      ->select('transaksi_order.*', DB::raw("CASE 
            WHEN IF(true, transaksi_order.harga_order_bersih <= (select SUM(nominal) FROM master_mutasi WHERE master_mutasi.transaksi_order_id = transaksi_order.id AND master_mutasi.jenis_transaksi = 'order')
              ,transaksi_order.harga_jual_bersih <= (select SUM(nominal) FROM master_mutasi WHERE master_mutasi.transaksi_order_id = transaksi_order.id AND master_mutasi.jenis_transaksi = 'jual'))
             THEN
               'lunas'
             ELSE
               'belum_lunas'
             END 
            AS status_lunas"));

    if ($param->subkon !== 'all') {
      $orders->where('m_subkon_id', '=', $param->subkon);
    }

    if ($param->status !== 'all') {
      $orders->when($param->status == 'lunas', function ($q) {
        $q->whereRaw(
          "IF(true, transaksi_order.harga_order_bersih <= (select SUM(nominal) FROM master_mutasi WHERE master_mutasi.transaksi_order_id = transaksi_order.id AND master_mutasi.jenis_transaksi = 'order')
          ,transaksi_order.harga_jual_bersih <= (select SUM(nominal) FROM master_mutasi WHERE master_mutasi.transaksi_order_id = transaksi_order.id AND master_mutasi.jenis_transaksi = 'jual'))"
        );
      })->when($param->status == 'belum_lunas', function ($q) {
        $q->whereRaw(
          "IF(true, transaksi_order.harga_order_bersih > (select SUM(nominal) FROM master_mutasi WHERE master_mutasi.transaksi_order_id = transaksi_order.id AND master_mutasi.jenis_transaksi = 'order')
          ,transaksi_order.harga_jual_bersih > (select SUM(nominal) FROM master_mutasi WHERE master_mutasi.transaksi_order_id = transaksi_order.id AND master_mutasi.jenis_transaksi = 'jual'))"
        );
      });
    }

    $cloneOrderQuery = clone $orders;
    $orders->whereBetween('tanggal_awal', [$param->tanggalAwal, $param->tanggalAkhir]);

    if ($export) {
      $orders = $orders->get();
    } else {
      $orders = $orders->paginate();
    }

    $totalHutang = (clone $cloneOrderQuery)
      ->withSum('mutasi_order as total_pembayaran', 'nominal')
      ->get()
      ->sum(fn($order) => $order->harga_order_bersih - $order->total_pembayaran);


    $totalHutangRange = (clone $cloneOrderQuery)
      ->whereBetween('tanggal_awal', [$param->tanggalAwal, $param->tanggalAkhir])
      ->withSum('mutasi_order as total_pembayaran', 'nominal')
      ->get()
      ->sum(fn($order) => $order->harga_order_bersih - $order->total_pembayaran);

    return [
      'orders' => $orders,
      'totalHutang' => $totalHutang,
      'totalHutangRange' => $totalHutangRange,
      'customer' => $penyewa ?? null
    ];
  }


  public static function getHutangSubkon(HutangSubkonParam $param, $export)
  {
    $subkon = SubkonModel::find($param->subkon);
    if (!$subkon) {
      throw new HttpException(404, 'Subkon not found');
    }

    $orders = OrderModel::query()
      ->where('m_subkon_id', $param->subkon)
      ->whereBetween('tanggal_awal', [$param->tanggalAwal, $param->tanggalAkhir]);

    if ($export) {
      $orders = $orders->get();
    } else {
      $orders = $orders->paginate();
    }

    $totalHutang = OrderModel::query()
      ->where('m_subkon_id', $param->subkon)
      ->withSum('mutasi_jual as total_pembayaran', 'nominal')
      ->get()
      ->sum(fn($order) => $order->harga_jual_bersih - $order->total_pembayaran);

    $totalHutangRange = OrderModel::query()
      ->where('m_subkon_id', $param->subkon)
      ->whereBetween('tanggal_awal', [$param->tanggalAwal, $param->tanggalAkhir])
      ->withSum('mutasi_jual as total_pembayaran', 'nominal')
      ->get()
      ->sum(fn($order) => $order->harga_jual_bersih - $order->total_pembayaran);

    return [
      'orders' => $orders,
      'totalHutang' => $totalHutang,
      'totalHutangRange' => $totalHutangRange,
      'subkon' => $subkon,
    ];
  }
}
