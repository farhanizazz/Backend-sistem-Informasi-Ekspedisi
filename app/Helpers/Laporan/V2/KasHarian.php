<?php

namespace App\Helpers\Laporan\V2;

use App\DataTransferObjects\KasHarianParam;
use App\Models\Master\MutasiModel;
use App\Models\Master\RekeningModel;

class KasHarian
{
  public RekeningModel $rekening;
  public function __construct(
    public KasHarianParam $param
  ) {
    // Validate if rekeningId exist
    $this->rekening = RekeningModel::find($param->rekeningId);
    if (!$this->rekening) {
      throw new \Exception("Rekening ID '{$param->rekeningId}' tidak ditemukan");
    }
  }

  public function getResources()
  {
    return MutasiModel::query()
      ->where('master_rekening_id', $this->param->rekeningId)
      ->where('tanggal_pembayaran', '>=', $this->param->tanggalAwal)
      ->where('tanggal_pembayaran', '<=', $this->param->tanggalAkhir)
      ->with([
        'pembuat',
        'master_rekening',
        'transaksi_order' => function ($q) {
          $q->select('id', 'no_transaksi');
        }
      ])
      ->orderBy('tanggal_pembayaran', 'asc')
      ->get();
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

      $pdf->loadView('generate.pdf.v2.kas-harian', [
        'filename' => 'Laporan Kas Harian',
        'data' => $resources,
        'rekening' => $this->rekening,
        'jangkaTanggal' => $jangkaTanggal
      ]);
      return $pdf->stream();
    }

    return response()->json([
      'status' => 'success',
      'data' => $resources
    ]);
  }
}
