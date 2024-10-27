<?php 
  namespace App\Repositories\Eloquent;

use App\Models\Transaksi\NotaBeliModel;
use App\Models\Transaksi\OrderModel;
use App\Models\Transaksi\ServisModel;
use App\Repositories\Contracts\LaporanInterface;

  class LaporanRepository implements LaporanInterface
  {
    private $model, $notaBeliModel;
    public function __construct(OrderModel $model, NotaBeliModel  $notaBeliModel)
    {
      $this->model = $model;
      $this->notaBeliModel = $notaBeliModel;
    }

    public function getLaporanPemasukanCV($tanggal_awal,$tanggal_akhir,$m_armada_id,$itemPerPage=10)
    {
      return $this->model
            ->whereBetween('tanggal_awal',[$tanggal_awal,$tanggal_akhir])
            ->when($m_armada_id, function($query) use ($m_armada_id){
              return $query->whereIn('m_armada_id',$m_armada_id);
            })
            ->with(['mutasi_order:nominal,transaksi_order_id,jenis_transaksi', 'mutasi_jual:nominal,transaksi_order_id,jenis_transaksi', 'mutasi_jalan:nominal,transaksi_order_id,jenis_transaksi', 'sopir:id,nama', 'penyewa:id,nama_perusahaan', 'subkon:id,nama_perusahaan', 'armada:id,nopol'])
            ->paginate($itemPerPage);
    }

    public function getLaporanPemasukanCVAll($tanggal_awal,$tanggal_akhir,$m_armada_id)
    {
      return $this->model
            ->whereBetween('tanggal_awal',[$tanggal_awal,$tanggal_akhir])
            ->when($m_armada_id, function($query) use ($m_armada_id){
              return $query->whereIn('m_armada_id',$m_armada_id);
            })
            ->with(['mutasi_order:nominal,transaksi_order_id,jenis_transaksi', 'mutasi_jual:nominal,transaksi_order_id,jenis_transaksi', 'mutasi_jalan:nominal,transaksi_order_id,jenis_transaksi', 'sopir:id,nama', 'penyewa:id,nama_perusahaan', 'subkon:id,nama_perusahaan', 'armada:id,nopol'])
            ->get();
    }

    public function getLaporanPemasukanKendaraanSubkon($tanggal_awal,$tanggal_akhir, $m_armada_id,$itemPerPage=10)
    {
      return $this->model
            ->whereBetween('tanggal_awal',[$tanggal_awal,$tanggal_akhir])
            ->when($m_armada_id, function($query) use ($m_armada_id){
              return $query->whereIn('m_armada_id',$m_armada_id);
            })
            ->where('status_kendaraan','Subkon')
            ->with(['mutasi_order:nominal,transaksi_order_id,jenis_transaksi', 'mutasi_jual:nominal,transaksi_order_id,jenis_transaksi', 'mutasi_jalan:nominal,transaksi_order_id,jenis_transaksi', 'sopir:id,nama', 'penyewa:id,nama_perusahaan', 'subkon:id,nama_perusahaan', 'armada:id,nopol'])
            ->paginate($itemPerPage);
    }

    public function getLaporanPemasukanKendaraanSendiri($tanggal_awal,$tanggal_akhir,$m_armada_id,$itemPerPage=10)
    {
      return $this->model
            ->whereBetween('tanggal_awal',[$tanggal_awal,$tanggal_akhir])
            ->when($m_armada_id, function($query) use ($m_armada_id){
              return $query->whereIn('m_armada_id',$m_armada_id);
            })
            ->where('status_kendaraan','Sendiri')
            ->with(['mutasi_order:nominal,transaksi_order_id,jenis_transaksi', 'mutasi_jual:nominal,transaksi_order_id,jenis_transaksi', 'mutasi_jalan:nominal,transaksi_order_id,jenis_transaksi', 'sopir:id,nama', 'penyewa:id,nama_perusahaan', 'subkon:id,nama_perusahaan', 'armada:id,nopol'])
            ->paginate($itemPerPage);
    }
    
    public function getLaporanPengeluaranServis($tanggal_awal,$tanggal_akhir,$m_armada_id,$itemPerPage=10)
    {
      return $this->notaBeliModel
            ->with(['servis:id,nomor_nota,tanggal_servis,master_armada_id', 'servis.master_armada:id,nopol'])
            ->whereHas('servis', function($query) use ($tanggal_awal,$tanggal_akhir,$m_armada_id){
              $query->whereBetween('tanggal_servis',[$tanggal_awal,$tanggal_akhir])
                    ->where('kategori_servis', 'servis')
                    ->when($m_armada_id, function($query) use ($m_armada_id){
                      return $query->whereIn('master_armada_id',$m_armada_id);
                    });
            })
            ->paginate($itemPerPage);
    }

    public function getLaporanPengeluaranLain($tanggal_awal,$tanggal_akhir,$m_armada_id,$itemPerPage=10)
    {
      return $this->notaBeliModel
            ->with(['servis:id,nomor_nota,tanggal_servis,master_armada_id', 'servis.master_armada:id,nopol'])
            ->whereHas('servis', function($query) use ($tanggal_awal,$tanggal_akhir,$m_armada_id){
              $query->whereBetween('tanggal_servis',[$tanggal_awal,$tanggal_akhir])
                    ->where('kategori_servis', 'lain')
                    ->when($m_armada_id, function($query) use ($m_armada_id){
                      return $query->whereIn('master_armada_id',$m_armada_id);
                    });
            })
            ->paginate($itemPerPage);
    }

    public function getLaporanPengeluaranSemua($tanggal_awal,$tanggal_akhir,$m_armada_id,$itemPerPage=10)
    {
      return $this->notaBeliModel
            ->with(['servis:id,nomor_nota,tanggal_servis,master_armada_id', 'servis.master_armada:id,nopol'])
            ->whereHas('servis', function($query) use ($tanggal_awal,$tanggal_akhir,$m_armada_id){
              $query->whereBetween('tanggal_servis',[$tanggal_awal,$tanggal_akhir])
              ->when($m_armada_id, function($query) use ($m_armada_id){
                return $query->whereIn('master_armada_id',$m_armada_id);
              });
            })
            ->whereBetween('tanggal_servis',[$tanggal_awal,$tanggal_akhir])
            ->paginate($itemPerPage);
    }
  }