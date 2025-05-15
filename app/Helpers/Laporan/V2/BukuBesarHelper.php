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
      DB::raw($this->_debetQuery() . ' as debet'),
      DB::raw($this->_kreditQuery() . ' as kredit'),
    ])->orderBy('tanggal_pembayaran', 'asc');

    return $query;
  }

  public function execute()
  {
    $query = $this->getResources();

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

    $rekening = 'Semua Rekening';
    if ($this->rekening) {
      $rekening = "{$this->rekening->nama_bank} ({$this->rekening->atas_nama})";
    }

    if ($this->param->export) {
      $mutasiResources = array_merge([$firstInit], $query->get()->toArray());

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
      return $this->_genPdf($mutasiResources, $rekening);
    }


    $perPage = request()->get('per_page', 10);
    $page = request()->get('page', 1) - 1;
    $startNo = $page * $perPage;

    $totalDebetBeforePagination = (clone $query)
      ->offset(0)
      ->limit($startNo)
      ->select(
        DB::raw($this->_debetQuery() . ' as debet'),
        DB::raw($this->_kreditQuery() . ' as kredit'),
      )
      ->pluck('debet')
      ->sum();
    $totalKreditBeforePagination = (clone $query)
      ->offset(0)
      ->limit($startNo)
      ->select(
        DB::raw($this->_debetQuery() . ' as debet'),
        DB::raw($this->_kreditQuery() . ' as kredit'),
      )
      ->pluck('kredit')
      ->sum();

    $firstInit['total'] = $totalKreditBeforePagination - $totalDebetBeforePagination;

    $arryMutasiResources = (clone $query)->paginate(
      $perPage,
      ['*'],
      'page',
      $page + 1
    );
    $mutasiResources = array_merge([$firstInit], $arryMutasiResources->toArray()['data']);

    // get total before pagination
    $total = $firstInit['total'];
    foreach ($mutasiResources as $index => $resource) {
      $total += $resource['kredit'];
      $total -= $resource['debet'];
      $resource['total'] = $total;
      $resource['no'] = $startNo + $index + 1;

      $mutasiResources[$index] = $resource;
    }

    // total

    $totalDebetAll = (clone $query)
      ->select(
        DB::raw($this->_debetQuery() . ' as debet'),
        DB::raw($this->_kreditQuery() . ' as kredit'),
      )
      ->pluck('debet')
      ->sum();
    $totalKreditAll = (clone $query)
      ->select(
        DB::raw($this->_debetQuery() . ' as debet'),
        DB::raw($this->_kreditQuery() . ' as kredit'),
      )
      ->pluck('kredit')
      ->sum();

    $meta = [
      'links' => $arryMutasiResources->getUrlRange(1, $arryMutasiResources->lastPage()),
      'total' => $arryMutasiResources->total(),
    ];

    return response()->json([
      'status' => 'success',
      'data' => new BukuBesarCollection(
        $mutasiResources,
        $rekening,
        $totalDebetAll,
        $totalKreditAll,
        $totalKreditAll - $totalDebetAll,
        $meta
      )
    ]);
  }

  private function _kreditQuery()
  {
    return 'CASE WHEN master_mutasi.jenis_transaksi in ("order", "pemasukan") THEN abs(master_mutasi.nominal) ELSE 0 END';
  }

  private function _debetQuery()
  {
    return 'CASE WHEN master_mutasi.jenis_transaksi in ("jual", "uang_jalan", "pengeluaran") THEN abs(master_mutasi.nominal) ELSE 0 END';
  }

  private function _genPdf($mutasiResources, $rekening)
  {

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

    $pdf->loadView('generate.pdf.v2.buku-besar', [
      'filename' => 'Buku Besar',
      'data' => $mutasiResources,
      'rekening' => $rekening,
      'all' => $this->param->rekeningId === 'all',
      'jangkaTanggal' => $jangkaTanggal
    ]);

    return $pdf->stream();
  }
}
