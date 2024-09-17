<?php 
  namespace App\Repositories\Eloquent;

  use App\Models\LaporanPemasukanCV;
use App\Models\Transaksi\OrderModel;
use App\Repositories\Contracts\LaporanPemasukanCVInterface;

  class LaporanPemasukanCVRepository implements LaporanPemasukanCVInterface
  {
    private $model;
    public function __construct(OrderModel $model)
    {
      $this->model = $model;
    }

    public function getLaporanPemasukanCV($tanggal_awal,$tanggal_akhir)
    {
      return $this->model
            ->whereBetween('tanggal_awal',[$tanggal_awal,$tanggal_akhir])
            ->with(['mutasi_order:nominal,transaksi_order_id,jenis_transaksi', 'mutasi_jual:nominal,transaksi_order_id,jenis_transaksi', 'mutasi_jalan:nominal,transaksi_order_id,jenis_transaksi', 'sopir:id,nama', 'penyewa:id,nama_perusahaan', 'subkon:id,nama_perusahaan', 'armada:id,nopol'])
            ->get();
    }
  }