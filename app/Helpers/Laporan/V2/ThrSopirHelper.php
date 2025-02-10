<?php

namespace App\Helpers\Laporan\V2;

use App\DataTransferObjects\ThrSopirParam;
use App\Http\Resources\LaporanV2\ThrSopirCollection;
use App\Models\Master\SopirModel;
use App\Models\Transaksi\OrderModel;

class ThrSopirHelper
{
  public SopirModel $sopir;
  public function __construct(
    public ThrSopirParam $param
  ) {
    // Validate if sopirId exist
    $this->sopir = SopirModel::find($param->sopirId);
    if (!$this->sopir) {
      throw new \Exception("Sopir ID '{$param->sopirId}' tidak ditemukan");
    }
  }

  public function getResources()
  {
    $query = OrderModel::query()
      ->where('m_sopir_id', $this->param->sopirId)
      ->where('tanggal_awal', '>=', $this->param->tanggalAwal)
      ->where('tanggal_awal', '<=', $this->param->tanggalAkhir)
      ->orderBy('tanggal_awal', 'asc');

    $total = (clone $query)->sum('potongan_wajib');

    if ($this->param->export) {
      $list = $query->get();
    } else {
      $list = $query->paginate();
    }

    return compact('list', 'total');
  }

  public function execute()
  {
    $resources = $this->getResources();

    if ($this->param->export) {
      $pdf = app('dompdf.wrapper');

      $tglAwal = format_date($this->param->tanggalAwal);
      $tglAkhir = format_date($this->param->tanggalAkhir);

      if ($this->param->tanggalAwal != $this->param->tanggalAkhir) {
        $jangkaTanggal = "{$tglAwal} s/d {$tglAkhir}";
      } else {
        $jangkaTanggal = "{$tglAwal}";
      }

      $pdf->loadView('generate.pdf.v2.thr-sopir', [
        'filename' => 'Laporan THR Sopir',
        'data' => $resources['list'],
        'total' => $resources['total'],
        'sopir' => $this->sopir,
        'jangkaTanggal' => $jangkaTanggal
      ]);

      return $pdf->stream();
    }

    return response()->json([
      'status' => 'success',
      'data' => new ThrSopirCollection(($resources['list']), $resources['total'])
    ]);
  }
}
