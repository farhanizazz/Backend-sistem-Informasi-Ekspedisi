<?php 
  namespace App\Repositories\Eloquent;

use App\Models\Transaksi\OrderModel;
use App\Repositories\Contracts\LaporanInterface;

  class LaporanRepository implements LaporanInterface
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

    public function getLaporanPemasukanKendaraanSubkon($tanggal_awal,$tanggal_akhir)
    {
      return $this->model
            ->whereBetween('tanggal_awal',[$tanggal_awal,$tanggal_akhir])
            ->where('status_kendaraan','Subkon')
            ->with(['mutasi_order:nominal,transaksi_order_id,jenis_transaksi', 'mutasi_jual:nominal,transaksi_order_id,jenis_transaksi', 'mutasi_jalan:nominal,transaksi_order_id,jenis_transaksi', 'sopir:id,nama', 'penyewa:id,nama_perusahaan', 'subkon:id,nama_perusahaan', 'armada:id,nopol'])
            ->get();
    }

    public function getLaporanPemasukanKendaraanSendiri($tanggal_awal,$tanggal_akhir,$m_armada_id)
    {
      return $this->model
            ->whereBetween('tanggal_awal',[$tanggal_awal,$tanggal_akhir])
            ->when($m_armada_id, function($query) use ($m_armada_id){
              return $query->where('m_armada_id',$m_armada_id);
            })
            ->where('status_kendaraan','Sendiri')
            ->with(['mutasi_order:nominal,transaksi_order_id,jenis_transaksi', 'mutasi_jual:nominal,transaksi_order_id,jenis_transaksi', 'mutasi_jalan:nominal,transaksi_order_id,jenis_transaksi', 'sopir:id,nama', 'penyewa:id,nama_perusahaan', 'subkon:id,nama_perusahaan', 'armada:id,nopol'])
            ->get();
    }
  }