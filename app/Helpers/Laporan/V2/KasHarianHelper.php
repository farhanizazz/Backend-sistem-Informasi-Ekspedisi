<?php

namespace App\Helpers\Laporan\V2;

use App\DataTransferObjects\KasHarianParam;
use App\Http\Resources\LaporanV2\KasHarianCollection;
use App\Models\Master\MutasiModel;
use App\Models\Master\RekeningModel;

class KasHarianHelper
{
  public RekeningModel $rekening;
  public function __construct(
    public KasHarianParam $param
  ) {
    // Validate if rekeningId exist
    if ($param->rekeningId !== 'all') {
      $this->rekening = RekeningModel::find($param->rekeningId);
      if (!$this->rekening) {
        throw new \Exception("Rekening ID '{$param->rekeningId}' tidak ditemukan");
      }
    }
  }

  public function getResources()
  {
    $query = MutasiModel::query()
      ->where('tanggal_pembayaran', '>=', $this->param->tanggalAwal)
      ->where('tanggal_pembayaran', '<=', $this->param->tanggalAkhir)
      ->with([
        'pembuat',
        'master_rekening',
        'transaksi_order' => function ($q) {
          $q->select('id', 'no_transaksi');
        }
      ])
      ->orderBy('tanggal_pembayaran', 'asc');

    if ($this->param->rekeningId !== 'all') {
      $query->where('master_rekening_id', $this->rekening->id);
    }

    if ($this->param->export) {
      return $query->get();
    } else {
      return $query->paginate();
    }
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
      'data' => new KasHarianCollection($resources)
    ]);
  }
}
