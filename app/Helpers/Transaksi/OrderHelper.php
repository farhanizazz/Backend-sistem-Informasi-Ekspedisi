<?php

namespace App\Helpers\Transaksi;

use App\Models\Master\ArmadaModel;
use App\Models\Master\RekeningModel;
use App\Models\Transaksi\OrderModel;
use Illuminate\Http\Request;

class OrderHelper
{

  private $armadaModel, $orderModel, $pph, $rekeningModel;
  public function __construct()
  {
    $this->armadaModel = new ArmadaModel();
    $this->orderModel = new OrderModel();
    $this->rekeningModel = new RekeningModel();
    $this->pph = config('global.pajak.pph');
  }

  public function create(Request $payload)
  {
    try {
      switch ($payload->status_kendaraan) {
        case 'Sendiri':
          # code...
          $dataSave = $payload->only([
            "tanggal_awal",
            "tanggal_akhir",
            "status_kendaraan",
            "status_kendaraan_sendiri",
            "no_transaksi",
            "status_surat_jalan",
            "m_penyewa_id",
            "muatan",
            "m_armada_id",
            "m_sopir_id",
            "asal",
            "tujuan",
            "harga_order",
            "harga_order_bersih",
            "bayar_harga_order",
            "status_harga_order",
            "biaya_lain_harga_order",
            "status_pajak",
            "total_pajak",
            "setor",
            "uang_jalan",
            "potongan_wajib",
            "biaya_lain_uang_jalan",
            "keterangan"
          ]);
          $result = $this->createOrderSendiri($dataSave);
          if (!$result['status']) {
            return $result;
          }
          return [
            "status" => true,
            "message" => "Berhasil membuat order",
            "data" => $result['data']
          ];
          break;
        case 'Subkon':
          # code...
          $dataSave = $payload->only([
            "tanggal_awal",
            "tanggal_akhir",
            "status_kendaraan",
            "status_kendaraan_sendiri",
            "no_transaksi",
            "status_surat_jalan",
            "m_penyewa_id",
            "muatan",
            "m_armada_id",
            "m_sopir_id",
            "asal",
            "tujuan",
            "harga_order",
            "harga_order_bersih",
            "bayar_harga_order",
            "total_bayar",
            "status_harga_order",
            "biaya_lain_harga_order",
            "status_pajak",
            "total_pajak",
            "m_subkon_id",
            "harga_jual",
            "bayar_harga_jual",
            "harga_jual_bersih",
            "status_harga_jual",
            "biaya_lain_harga_jual",
            "keterangan"
          ]);
      
          $result = $this->createOrderSubkon($dataSave);
          if (!$result['status']) {
            return $result;
          }
          return [
            "status" => true,
            "message" => "Berhasil membuat order",
            "data" => $result['data']
          ];
          break;
        default:
          # code...
          return [
            "status" => false,
            "message" => "Status kendaraan tidak ditemukan",
            "dev" => "Status kendaraan tidak ditemukan"
          ];
          break;
      }
    } catch (\Throwable $th) {
      //throw $th;
      return [
        "status" => false,
        "message" => "Gagal membuat order",
        "dev" => $th->getMessage()
      ];
    }
  }

  public function createOrderSendiri($payload)
  {
    $noTransaksi = $this->generateNoTransaksi($payload['m_armada_id']);
    if (!$noTransaksi['status']) {
      return $noTransaksi;
    }
    // set no_transaksi
    $payload['no_transaksi'] = $noTransaksi['data'];
    $passedData = $this->passedDataKendSendiri($payload);
    $result = $this->orderModel->create($passedData);
    return [
      "status" => true,
      "message" => "Berhasil membuat order",
      "data" => $result
    ];
  }

  public function createOrderSubkon($payload)
  {
    $noTransaksi = $this->generateNoTransaksi($payload['m_armada_id']);
    if (!$noTransaksi['status']) {
      return $noTransaksi;
    }
    // set no_transaksi
    $payload['no_transaksi'] = $noTransaksi['data'];
    $passedData = $this->passedDataKendSubkon($payload);
    $result = $this->orderModel->create($passedData);
    return [
      "status" => true,
      "message" => "Berhasil membuat order",
      "data" => $result
    ];
  }

  public function generateNoTransaksi($m_armada_id)
  {
    $armada = $this->armadaModel->where('id', $m_armada_id)->first();
    $order = $this->orderModel->whereDate('created_at', date("Y-m-d"))->orderBy('id', 'desc')->select('no_transaksi')->first();
    if ($armada == null) {
      return [
        "status" => false,
        "message" => "Armada tidak ditemukan"
      ];
    }
    if ($order == null) {
      $no_transaksi = str_replace(" ", "", $armada->nopol) . '.' . date("Ymd") . "." . str_pad(1, 3, "0", STR_PAD_LEFT);
    } else {
      $no_transaksi = str_replace(" ", "", $armada->nopol) . '.' . date("Ymd") . "." . str_pad((explode('.', $order->no_transaksi)[2] + 1), 3, "0", STR_PAD_LEFT);
    }
    return [
      "status" => true,
      "message" => "Berhasil generate no transaksi",
      "data" => $no_transaksi
    ];
  }

  public function passedDataKendSendiri($payload)
  {
    // set pajak
    if ($payload['status_pajak'] == 'ya') {
      $payload['total_pajak'] = $this->hitungPajak($payload['harga_order'], 'pph');
    } else {
      $payload['total_pajak'] = 0;
    }

    // set total biaya lain
    if (!empty($payload['biaya_lain_harga_order'])) {
      $biaya_lain_harga_order = $this->hitungTotalBiayaLain($payload['biaya_lain_harga_order']);
    } else {
      $biaya_lain_harga_order = 0;
    }

    // set harga order
    $payload['harga_order_bersih'] = $payload['harga_order'] + $biaya_lain_harga_order - $payload['total_pajak'];

    if (!empty($payload['biaya_lain_uang_jalan'])) {
      $biaya_lain_uang_jalan = $this->hitungTotalBiayaLain($payload['biaya_lain_uang_jalan']);
    } else {
      $biaya_lain_uang_jalan = 0;
    }

    // set uang jalan
    $payload['uang_jalan_bersih'] = $payload['uang_jalan'] + $biaya_lain_uang_jalan - $payload['potongan_wajib'];
    return $payload;
  }

  public function passedDataKendSubkon($payload)
  {
    // set pajak
    if ($payload['status_pajak'] == 'ya') {
      $payload['total_pajak'] = $this->hitungPajak($payload['harga_order'], 'pph');
    } else {
      $payload['total_pajak'] = 0;
    }

    // set total biaya lain
    if (!empty($payload['biaya_lain_harga_order'])) {
      $biaya_lain_harga_order = $this->hitungTotalBiayaLain($payload['biaya_lain_harga_order']);
    } else {
      $biaya_lain_harga_order = 0;
    }

    // set harga order
    $payload['harga_order_bersih'] = $payload['harga_order'] + $biaya_lain_harga_order - $payload['total_pajak'];

    if (!empty($payload['biaya_lain_harga_jual'])) {
      $biaya_lain_harga_jual = $this->hitungTotalBiayaLain($payload['biaya_lain_harga_jual']);
    } else {
      $biaya_lain_harga_jual = 0;
    }

    // set uang jalan
    $payload['harga_jual_bersih'] = $payload['harga_jual'] + $biaya_lain_harga_jual;
    return $payload;
  }

  public function hitungPajak($total, $jenis)
  {
    switch ($jenis) {
      case 'pph':
        # code...
        return $total * $this->pph / 100;
        break;

      default:
        # code...
        return 0;
        break;
    }
  }

  public function hitungTotalBiayaLain($listId)
  {
    $listBiayaLain = $this->rekeningModel->whereIn('id', $listId)->get();
    $total = 0;
    foreach ($listBiayaLain as $key => $value) {
      $total += $value->nominal;
    }
    return $total;
  }


  public function update(Request $payload, $id)
  {
    try {
      switch ($payload->status_kendaraan) {
        case 'Sendiri':
          # code...
          $dataSave = $payload->only([
            "no_transaksi",
            "tanggal_awal",
            "tanggal_akhir",
            "status_kendaraaan",
            "status_kendaraan_sendiri",
            "no_transaksi",
            "status_surat_jalan",
            "m_penyewa_id",
            "muatan",
            "m_armada_id",
            "m_sopir_id",
            "asal",
            "tujuan",
            "harga_order",
            "harga_order_bersih",
            "bayar_harga_order",
            "status_harga_order",
            "biaya_lain_harga_order",
            "status_pajak",
            "total_pajak",
            "setor",
            "uang_jalan",
            "potongan_wajib",
            "biaya_lain_uang_jalan",
            "keterangan"
          ]);
          $result = $this->passedDataKendSendiri($dataSave);
          $isSuccess = $this->orderModel->where('id', $id)->update($result);
          if (!$isSuccess) {
            return [
              "status" => false,
              "message" => "Gagal membuat order",
              "dev" => "Gagal membuat order"
            ];
          }
          return [
            "status" => true,
            "message" => "Berhasil mengedit order",
          ];
          break;
        case 'Subkon':
          # code...
          $dataSave = $payload->only([
            "no_transaksi",
            "tanggal_awal",
            "tanggal_akhir",
            "status_kendaraaan",
            "status_kendaraan_sendiri",
            "no_transaksi",
            "status_surat_jalan",
            "m_penyewa_id",
            "muatan",
            "m_armada_id",
            "m_sopir_id",
            "asal",
            "tujuan",
            "harga_order",
            "harga_order_bersih",
            "bayar_harga_order",
            "total_bayar",
            "status_harga_order",
            "biaya_lain_harga_order",
            "status_pajak",
            "total_pajak",
            "m_subkon_id",
            "harga_jual",
            "bayar_harga_jual",
            "harga_jual_bersih",
            "status_harga_jual",
            "biaya_lain_harga_jual",
            "keterangan"
          ]);
          $result = $this->passedDataKendSubkon($dataSave);
          $isSuccess = $this->orderModel->where('id', $id)->update($result);
          if (!$isSuccess) {
            return [
              "status" => false,
              "message" => "Gagal membuat order",
              "dev" => "Gagal membuat order"
            ];
          }
          return [
            "status" => true,
            "message" => "Berhasil mengedit order"
          ];
          break;
        default:
          # code...
          return [
            "status" => false,
            "message" => "Status kendaraan tidak ditemukan",
            "dev" => "Status kendaraan tidak ditemukan"
          ];
          break;
      }
    } catch (\Throwable $th) {
      //throw $th;
      return [
        "status" => false,
        "message" => "Gagal mengedit order",
        "dev" => $th->getMessage()
      ];
    }
  }
}
