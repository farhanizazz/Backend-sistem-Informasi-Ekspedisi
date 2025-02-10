<?php

namespace App\Helpers\Laporan\V2;

use App\DataTransferObjects\ArmadaRugiLabaParam;
use App\Http\Resources\LaporanV2\ArmadaRugiLabaCollection;
use App\Models\Master\ArmadaModel;
use App\Models\Transaksi\OrderModel;
use App\Models\Transaksi\ServisModel;
use Illuminate\Support\Facades\DB;

class ArmadaRugiLabaHelper
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

  public function getResources()
  {
    // Pemasukan
    $subQueryPemasukan = OrderModel::query();
    if ($this->param->armadaId !== "all") {
      $subQueryPemasukan->where('master_armada.id', $this->param->armadaId);
    }

    if ($this->param->tanggalAwal && $this->param->tanggalAkhir) {
      $subQueryPemasukan->whereBetween('transaksi_order.tanggal_awal', [$this->param->tanggalAwal, $this->param->tanggalAkhir]);
    }

    // Pengeluaran
    $subQueryPengeluaran = ServisModel::query()
      ->join('servis_mutasi', 'servis_mutasi.servis_id', '=', 'servis.id', 'left')
      ->join('master_mutasi', 'master_mutasi.id', '=', 'servis_mutasi.master_mutasi_id', 'left');
    if ($this->param->armadaId !== "all") {
      $subQueryPengeluaran->where('master_armada.id', $this->param->armadaId);
    }

    if ($this->param->tanggalAwal && $this->param->tanggalAkhir) {
      $subQueryPengeluaran->whereBetween('tanggal_servis', [$this->param->tanggalAwal, $this->param->tanggalAkhir]);
    }

    $query = ArmadaModel::query()
      ->select(
        [
          'master_armada.id',
          'master_armada.nopol',
          'pemasukan_setor' => $subQueryPemasukan
            ->whereRaw('transaksi_order.m_armada_id = master_armada.id')
            ->select(DB::raw('COALESCE(SUM(setor), 0) as pemasukan_setor')),
          'pengeluaran_servis' => $subQueryPengeluaran
            ->whereRaw('servis.master_armada_id = master_armada.id')
            ->select(DB::raw('COALESCE(SUM(master_mutasi.nominal), 0) as pengeluaran_servis')),
        ]
      );

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


    // Total
    $totalSetor = $resources->sum('pemasukan_setor');
    $totalServis = $resources->sum('pengeluaran_servis');
    $totalAkhir = $totalSetor - $totalServis;

    if ($this->param->export) {
      $pdf = app('dompdf.wrapper');

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

      $pdf->loadView('generate.pdf.v2.armada-rugi-laba', [
        'filename' => 'Laporan Laba Rugi Armada',
        'data' => $resources,
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
