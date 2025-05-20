<?php

namespace App\Helpers\Laporan\V2;

use App\DataTransferObjects\ArmadaRugiLabaParam;
use App\Http\Resources\LaporanV2\ArmadaRugiLabaCollection;
use App\Models\Master\ArmadaModel;
use App\Models\Transaksi\OrderModel;
use App\Models\Transaksi\ServisModel;
use Illuminate\Support\Facades\DB;

class ArmadaRugiLabaDetailHelper
{
  public ?ArmadaModel $armada = null;
  public function __construct(
    public ArmadaRugiLabaParam $param
  ) {
    // Validate if armadaId exist
    if ($param->armadaId !== "all") {
      $this->armada = ArmadaModel::find($param->armadaId);
      if (!$this->armada) {
        throw new \Exception("Armada ID '{$param->armadaId}' tidak ditemukan");
      }
    }
  }

  public function getResources($pemasukkanQuery = null)
  {
    // Pemasukan
    $pemasukkanQuery = $pemasukkanQuery ?? OrderModel::query();
    $subQueryPemasukan = $pemasukkanQuery
      ->join('master_armada', 'master_armada.id', '=', 'transaksi_order.m_armada_id', 'left');
    if ($this->param->armadaId !== "all") {
      $subQueryPemasukan->where('master_armada.id', $this->param->armadaId);
    }

    if ($this->param->tanggalAwal && $this->param->tanggalAkhir) {
      $subQueryPemasukan->whereBetween('transaksi_order.tanggal_awal', [$this->param->tanggalAwal, $this->param->tanggalAkhir]);
    }

    // Pengeluaran
    $subQueryPengeluaran = ServisModel::query()
      ->join('nota_beli', 'nota_beli.servis_id', '=', 'servis.id', 'left')
      ->join('servis_mutasi', 'servis_mutasi.servis_id', '=', 'servis.id', 'left')
      ->join('master_mutasi', 'master_mutasi.id', '=', 'servis_mutasi.master_mutasi_id', 'left')
      ->join('master_armada', 'master_armada.id', '=', 'servis.master_armada_id', 'left');
    if ($this->param->armadaId !== "all") {
      $subQueryPengeluaran->where('master_armada.id', $this->param->armadaId);
    }

    if ($this->param->tanggalAwal && $this->param->tanggalAkhir) {
      $subQueryPengeluaran->whereBetween('tanggal_servis', [$this->param->tanggalAwal, $this->param->tanggalAkhir]);
    }

    $firstQuery = $subQueryPemasukan
      ->whereRaw('transaksi_order.m_armada_id = master_armada.id')
      ->select([
        DB::raw('`transaksi_order`.`tanggal_awal` as tanggal'),
        DB::raw('SUBSTRING_INDEX(transaksi_order.no_transaksi, ".", 1) as nopol'),
        DB::raw('"Pemasukan" as jenis_transaksi'),
        DB::raw('`transaksi_order`.`no_transaksi` as nota'),
        DB::raw('"" as nama_toko'),
        DB::raw('`transaksi_order`.`keterangan` as keterangan'),
        DB::raw('`setor` as harga'),
        DB::raw('1 as jumlah_satuan'),
        DB::raw('`setor` as sub_total'),
        DB::raw('master_armada.id as armada_id'),
      ]);
    $secondQuery = $subQueryPengeluaran
      ->whereRaw('servis.master_armada_id = master_armada.id')
      ->select([
        DB::raw('`tanggal_servis` as tanggal'),
        DB::raw('`nopol` as nopol'),
        DB::raw('"Pengeluaran" as jenis_transaksi'),
        DB::raw('`nomor_nota` as nota'),
        DB::raw('`nama_toko` as nama_toko'),
        DB::raw('`nota_beli`.`nama_barang` as keterangan'),
        DB::raw('`nota_beli`.`harga` as harga'),
        DB::raw('`nota_beli`.`jumlah` as jumlah_satuan'),
        DB::raw('(`nota_beli`.`jumlah` * `nota_beli`.`harga`) as sub_total'),
        DB::raw('master_armada.id as armada_id'),
      ]);

    $query = $firstQuery->union($secondQuery);
    // $query = $firstQuery;

    // add order by to the union query
    $query->orderBy('tanggal', 'asc')
      ->orderBy('nopol', 'asc');

    if ($this->param->armadaId !== "all") {
      $arrArmada = explode(",", $this->param->armadaId);
      $query->whereIn('master_armada.id', $arrArmada);
    }

    if ($this->param->export) {
      return $query->get();
    }

    return $query->paginate();
  }

  public function execute()
  {
    $resources = $this->getResources();

    $totalSetor = $resources->where('jenis_transaksi', '=', 'Pemasukan')->sum('sub_total');
    $totalServis = $resources->where('jenis_transaksi', '=', 'Pengeluaran')->sum('sub_total');
    $totalAkhir = $totalSetor - $totalServis;

    // Grouping by armada_id and tanggal
    $newResource = $resources->groupBy(function ($item) {
      return $item->tanggal . '.' . $item->armada_id;
    });

    if ($this->param->export) {
      $pdf = app('dompdf.wrapper');

      // set paper A3
      $pdf->setPaper('A4', 'landscape');

      $tglAwal = format_date($this->param->tanggalAwal);
      $tglAkhir = format_date($this->param->tanggalAkhir);

      if ($this->param->tanggalAwal != $this->param->tanggalAkhir) {
        $jangkaTanggal = "{$tglAwal} s/d {$tglAkhir}";
      } else if ($this->param->tanggalAwal == null) {
        $jangkaTanggal = "Semua Tanggal";
      } else {
        $jangkaTanggal = "{$tglAwal}";
      }

      $armada = 'Semua armada';
      if ($this->armada) {
        $armada = 'Spesifik';
      }

      $pdf->loadView('generate.pdf.v2.armada-rugi-laba-detail', [
        'filename' => 'Laporan Laba Rugi Armada',
        'data' => $newResource->toArray(),
        'armada' => $armada,
        'totalSetor' => $totalSetor,
        'totalServis' => $totalServis,
        'totalAkhir' => $totalAkhir,
        'all' => $this->param->armadaId === 'all',
        'jangkaTanggal' => $jangkaTanggal
      ]);

      return $pdf->stream();
    }

    return response()->json([
      'status' => 'success',
      'data' => new ArmadaRugiLabaCollection(
        $resources,
        $totalSetor,
        $totalServis,
        $totalAkhir
      )
    ]);
  }
}
