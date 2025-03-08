<?php

namespace App\Helpers\Laporan\V2;

use App\DataTransferObjects\BukuBesarParam;
use App\Http\Resources\LaporanV2\ArmadaRugiLabaCollection;
use App\Http\Resources\LaporanV2\BukuBesarCollection;
use App\Models\Master\ArmadaModel;
use App\Models\Master\MutasiModel;
use App\Models\Master\RekeningModel;
use App\Models\Transaksi\OrderModel;
use App\Models\Transaksi\ServisModel;
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
        throw new \Exception("Rekening ID '{$param->rekeningId}' tidak ditemukan");
      }
    }
  }

  public function getResources()
  {
    $query = MutasiModel::query()
      ->leftJoin('transaksi_order', 'transaksi_order.id', '=', 'master_mutasi.transaksi_order_id')
      ->leftJoin('servis', function ($q) {
        $q->on('servis.id', '=', 'master_mutasi.model_id');
        $q->on('master_mutasi.model_type', 'like', DB::raw('"App%Models%Transaksi%ServisModel"'));
      });

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
      DB::raw('CASE WHEN transaksi_order.no_transaksi IS NOT NULL THEN transaksi_order.no_transaksi ELSE servis.nomor_nota END as no_transaksi'),
      'master_mutasi.jenis_transaksi',
      'master_mutasi.asal_transaksi',
      'master_mutasi.keterangan',
      DB::raw('CASE WHEN master_mutasi.jenis_transaksi in ("jual", "uang_jalan", "pengeluaran") THEN abs(master_mutasi.nominal) ELSE 0 END as debet'),
      DB::raw('CASE WHEN master_mutasi.jenis_transaksi in ("order", "pemasukan") THEN abs(master_mutasi.nominal) ELSE 0 END as kredit'),
    ])->orderBy('tanggal_pembayaran', 'asc');

    return $query->get()->toArray();
  }

  public function execute()
  {
    $mutasiResources = $this->getResources();

    $firstInit = [
      "no" => 1,
      "tanggal" => $this->param->tanggalAwal,
      "no_transaksi" => "SA",
      "jenis_transaksi" => "Saldo Awal",
      "asal_transaksi" => "-",
      "keterangan" => "-",
      "debet" => 0,
      "kredit" => 0,
      "total" => 0,
    ];

    $mutasiResources = array_merge([$firstInit], $mutasiResources);
    // $detailOrderResources = [];

    // foreach ($orderResources as $orderResource) {
    //   $items = [
    //     [
    //       "name" => "Biaya Lain Harga Order",
    //       "items" => $orderResource->biayaLainHargaOrderArr
    //     ],
    //     [
    //       "name" => "Biaya Lain Uang Jalan",
    //       "items" => $orderResource->biayaLainUangJalanArr
    //     ],
    //     [
    //       "name" => "Biaya Lain Harga Jual",
    //       "items" => $orderResource->biayaLainHargaJualArr
    //     ],
    //   ];
    //   foreach ($items as $subItems) {
    //     foreach ($subItems['items'] as $item) {
    //       $debet = 0;
    //       $kredit = 0;

    //       if (strtolower($item['sifat']) === 'menambahkan') {
    //         $kredit = abs($item['nominal']);
    //       } else {
    //         $debet = abs($item['nominal']);
    //       }

    //       $detailOrderResources[] = [
    //         "no" => count($detailOrderResources) + 1,
    //         "tipe" => $subItems['name'],
    //         "no_transaksi" => $orderResource->no_transaksi,
    //         "keterangan" => $item['nama'],
    //         "debet" => $debet,
    //         "kredit" => $kredit,
    //       ];
    //     }
    //   }
    // }

    if (count($mutasiResources) > 1500) {
      // if record greather than 2000, must adjust memory allocation
      ini_set('memory_limit', '-1');
      // set execution time to 60 seconds
      set_time_limit(60);
    }

    $total = 0;
    foreach ($mutasiResources as $index => $resource) {
      $total += $resource['kredit'] - $resource['debet'];
      $resource['total'] = $total;
      $resource['no'] = $index + 1;

      $mutasiResources[$index] = $resource;
    }

    if ($this->param->export) {
      $pdf = app('dompdf.wrapper');

      // Enable isHtml5ParserEnabled for better parsing
      $pdf->set_option('isHtml5ParserEnabled', true);

      // Reduce memory footprint
      $pdf->set_option('isPhpEnabled', false);



      $tglAwal = format_date($this->param->tanggalAwal);
      $tglAkhir = format_date($this->param->tanggalAkhir);


      foreach ($mutasiResources as $index => $resource) {
        $resource['jenis_transaksi'] = ucwords(str_replace("_", " ", $resource['jenis_transaksi']));
        $resource['asal_transaksi'] = ucwords(str_replace("_", " ", $resource['asal_transaksi']));
        $resource['tanggal'] = format_date($resource['tanggal']);
        $resource['debet'] = rupiah($resource['debet']);
        $resource['kredit'] = rupiah($resource['kredit']);
        $resource['total'] = rupiah($resource['total']);
        $mutasiResources[$index] = $resource;
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
        'data' => $mutasiResources,
        'rekening' => $rekening,
        'all' => $this->param->rekeningId === 'all',
        'jangkaTanggal' => $jangkaTanggal
      ]);

      return $pdf->stream();
    }

    return response()->json([
      'status' => 'success',
      'data' => new BukuBesarCollection(
        $mutasiResources
      )
    ]);
  }
}
