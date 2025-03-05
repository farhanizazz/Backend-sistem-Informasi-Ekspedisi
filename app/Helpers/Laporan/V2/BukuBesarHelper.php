<?php

namespace App\Helpers\Laporan\V2;

use App\DataTransferObjects\BukuBesarParam;
use App\Http\Resources\LaporanV2\ArmadaRugiLabaCollection;
use App\Http\Resources\LaporanV2\BukuBesarCollection;
use App\Models\Master\ArmadaModel;
use App\Models\Master\MutasiModel;
use App\Models\Master\RekeningModel;
use App\Models\Transaksi\OrderModel;
use Illuminate\Support\Facades\DB;

class BukuBesarHelper
{
  public ?RekeningModel $rekening = null;
  public function __construct(
    public BukuBesarParam $param
  ) {
    // Validate if armadaId exist
    if ($param->rekeningId !== "all") {
      $this->rekening = RekeningModel::find($param->rekeningId);
      if (!$this->rekening) {
        throw new \Exception("Armada ID '{$param->rekeningId}' tidak ditemukan");
      }
    }
  }

  public function getResources()
  {
    $query = MutasiModel::query()
      ->join('transaksi_order', 'transaksi_order.id', '=', 'master_mutasi.transaksi_order_id');


    if ($this->param->rekeningId !== "all") {
      $query->where('master_rekening_id', $this->param->rekeningId);
    }

    if ($this->param->tanggalAwal && $this->param->tanggalAkhir) {
      $query->whereBetween('tanggal_pembayaran', [$this->param->tanggalAwal, $this->param->tanggalAkhir]);
    }

    // order
    // jual
    // uang_jalan
    // pengeluaran

    // if order is kredit
    // else is debit

    $query = $query->select([
      DB::raw('tanggal_pembayaran as tanggal'),
      'transaksi_order.no_transaksi',
      'master_mutasi.keterangan',
      DB::raw('CASE WHEN master_mutasi.jenis_transaksi in ("jual", "uang_jalan", "pengeluaran") THEN abs(master_mutasi.nominal) ELSE 0 END as debet'),
      DB::raw('CASE WHEN master_mutasi.jenis_transaksi in ("order") THEN abs(master_mutasi.nominal) ELSE 0 END as kredit'),
    ])->orderBy('tanggal_pembayaran', 'asc');

    return $query->get()->toArray();
  }

  public function execute()
  {
    $resources = $this->getResources();


    $firstInit = [
      "no" => 1,
      "tanggal" => $this->param->tanggalAwal,
      "no_transaksi" => "SA",
      "keterangan" => "-",
      "debet" => 0,
      "kredit" => 0,
      "total" => 0,
    ];

    $resources = array_merge([$firstInit], $resources);

    if (count($resources) > 1500) {
      // if record greather than 2000, must adjust memory allocation
      ini_set('memory_limit', '-1');
      // set execution time to 60 seconds
      set_time_limit(60);
    }

    $total = 0;
    foreach ($resources as $index => $resource) {
      $total += $resource['kredit'] - $resource['debet'];
      $resource['total'] = $total;
      $resource['no'] = $index + 1;

      $resources[$index] = $resource;
    }

    if ($this->param->export) {
      $pdf = app('dompdf.wrapper');
      // $pdf->set_paper('A3', 'landscape');

      // Enable isHtml5ParserEnabled for better parsing
      $pdf->set_option('isHtml5ParserEnabled', true);

      // Reduce memory footprint
      $pdf->set_option('isPhpEnabled', false);



      $tglAwal = format_date($this->param->tanggalAwal);
      $tglAkhir = format_date($this->param->tanggalAkhir);


      foreach ($resources as $index => $resource) {
        $resource['tanggal'] = format_date($resource['tanggal']);
        $resource['debet'] = rupiah($resource['debet']);
        $resource['kredit'] = rupiah($resource['kredit']);
        $resource['total'] = rupiah($resource['total']);
        $resources[$index] = $resource;
      }

      if ($this->param->tanggalAwal != $this->param->tanggalAkhir) {
        $jangkaTanggal = "{$tglAwal} s/d {$tglAkhir}";
      } else if ($this->param->tanggalAwal == null) {
        $jangkaTanggal = "Semua Tanggal";
      } else {
        $jangkaTanggal = "{$tglAwal}";
      }

      $rekening = 'Semua Rekening';
      if ($this->rekening) {
        $rekening = 'Spesifik';
      }

      $pdf->loadView('generate.pdf.v2.buku-besar', [
        'filename' => 'Buku Besar',
        'data' => $resources,
        'rekening' => $rekening,
        'all' => $this->param->rekeningId === 'all',
        'jangkaTanggal' => $jangkaTanggal
      ]);

      return $pdf->stream();
    }

    return response()->json([
      'status' => 'success',
      'data' => new BukuBesarCollection(
        $resources
      )
    ]);
  }
}
