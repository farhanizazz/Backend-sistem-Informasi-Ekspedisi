<?php

namespace App\Models\Transaksi;

use App\Enums\TipeKalkulasiSisaUangJalanEnum;
use App\Models\Master\RekeningModel;
use App\Models\Master\TambahanModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class OrderModel extends Model
{
    use HasFactory;

    protected $table = 'transaksi_order';
    protected $fillable = [
        'tanggal_awal',
        'tanggal_akhir',
        'status_kendaraan',
        'status_kendaraan_sendiri',
        'no_transaksi',
        'status_surat_jalan',
        'm_penyewa_id',
        'muatan',
        'm_armada_id',
        'm_sopir_id',
        'asal',
        'tujuan',
        'harga_order',
        'harga_order_bersih',
        'bayar_harga_order',
        'biaya_lain_harga_order',
        'status_pajak',
        'total_pajak',
        'setor',
        'uang_jalan',
        'uang_jalan_bersih',
        'potongan_wajib',
        'biaya_lain_uang_jalan',
        'm_subkon_id',
        'harga_jual',
        'bayar_harga_jual',
        'harga_jual_bersih',
        'biaya_lain_harga_jual',
        'keterangan',
        'catatan_surat_jalan',
        'nopol_subkon',
        'sopir_subkon',
        'ppn',
        'nomor_sj',
        'nomor_po',
        'nomor_do',
    ];

    protected $casts = [
        'biaya_lain_harga_order' => 'array',
        'biaya_lain_uang_jalan' => 'array',
        'biaya_lain_harga_jual' => 'array',
    ];

    protected $appends = [
        'sisa_tagihan',
        'sisa_hutang_ke_subkon',
        'biaya_lain_harga_order_arr',
        'biaya_lain_harga_jual_arr',
        'biaya_lain_uang_jalan_arr',
    ];

    public function penyewa()
    {
        return $this->belongsTo('App\Models\Master\PenyewaModel', 'm_penyewa_id');
    }

    public function armada()
    {
        return $this->belongsTo('App\Models\Master\ArmadaModel', 'm_armada_id');
    }

    public function sopir()
    {
        return $this->belongsTo('App\Models\Master\SopirModel', 'm_sopir_id');
    }

    public function subkon()
    {
        return $this->belongsTo('App\Models\Master\SubkonModel', 'm_subkon_id');
    }

    public function getBiayaLainHargaOrderArrAttribute()
    {
        if ($this->biaya_lain_harga_order == null) {
            return [];
        }
        return array_map(function ($data) {
            $rekeningData = TambahanModel::where('id', $data['m_tambahan_id'] ?? '')->first();
            $sifat = $rekeningData->sifat ?? '';
            $nama = $rekeningData->nama ?? '';
            return array_merge($data, ['sifat' => $sifat, 'nama' => $nama]);
        }, $this->biaya_lain_harga_order);
    }

    public function getBiayaLainUangJalanArrAttribute()
    {
        if ($this->biaya_lain_uang_jalan == null) {
            return [];
        }
        return array_map(function ($data) {
            $rekeningData = TambahanModel::where('id', $data['m_tambahan_id'])->first();
            $sifat = $rekeningData->sifat ?? '';
            $nama = $rekeningData->nama ?? '';
            return array_merge($data, ['sifat' => $sifat, 'nama' => $nama]);
        }, $this->biaya_lain_uang_jalan);
    }

    public function getBiayaLainHargaJualArrAttribute()
    {
        if ($this->biaya_lain_harga_jual == null) {
            return [];
        }
        return array_map(function ($data) {
            $rekeningData = TambahanModel::where('id', $data['m_tambahan_id'])->first();
            $sifat = $rekeningData->sifat ?? '';
            $nama = $rekeningData->nama ?? '';
            return array_merge($data, ['sifat' => $sifat, 'nama' => $nama]);
        }, $this->biaya_lain_harga_jual);
    }



    public function getSisaTagihanAttribute()
    {
        $sisa_tagihan = $this->harga_order_bersih - $this->bayar_harga_order;
        return $sisa_tagihan;
    }

    public function getSisaHutangKeSubkonAttribute()
    {
        $sisa_hutang_ke_subkon = $this->harga_jual_bersih - $this->bayar_harga_jual;
        return $sisa_hutang_ke_subkon;
    }

    public function mutasi()
    {
        return $this->hasMany('App\Models\Master\MutasiModel', 'transaksi_order_id');
    }

    public function mutasi_order()
    {
        return $this->hasMany('App\Models\Master\MutasiModel', 'transaksi_order_id')->where('jenis_transaksi', 'order');
    }

    public function mutasi_jual()
    {
        return $this->hasMany('App\Models\Master\MutasiModel', 'transaksi_order_id')->where('jenis_transaksi', 'jual');
    }

    public function mutasi_jalan()
    {
        return $this->hasMany('App\Models\Master\MutasiModel', 'transaksi_order_id')->where('jenis_transaksi', 'uang_jalan');
    }

    public function index($filter = [], $itemPerPage = 20)
    {
        $data = $this->when($filter['status_kendaraan'], function ($query) use ($filter) {
            $query->where("status_kendaraan", $filter['status_kendaraan']);
        })->with(['penyewa', 'armada', 'sopir', 'subkon'])
            ->when($filter['nama_penyewa'] && isset($filter['nama_penyewa']), function ($query) use ($filter) {
                $query->whereHas('penyewa', function ($query2) use ($filter) {
                    $query2->where('nama_perusahaan', 'like', '%' . $filter['nama_penyewa'] . '%');
                });
            })
            ->when(isset($filter['biaya_lain']) && $filter['biaya_lain'], function ($query) use ($filter) {
                $data = DB::select("(SELECT id,nama FROM master_tambahan where nama like '%" . $filter['biaya_lain'] . "%')");
                if (count($data) == 0) {
                    return $query->whereRaw('1=0');
                }
                $query->where(function ($query2) use ($data) {
                    foreach ($data as $key => $value) {
                        $query2->orWhere("biaya_lain_harga_order", "like", '%m_tambahan_id":' . $value->id . ",%")
                            ->orWhere("biaya_lain_uang_jalan", "like", '%m_tambahan_id":' . $value->id . ",%")
                            ->orWhere("biaya_lain_harga_jual", "like", '%m_tambahan_id":' . $value->id . ",%");
                    }
                });
            })
            ->when($filter['cari'], function ($query) use ($filter) {
                $query->where(function ($query2) use ($filter) {
                    $query2->where('no_transaksi', 'like', '%' . $filter['cari'] . '%')
                        ->orWhereHas('penyewa', function ($query3) use ($filter) {
                            $query3->where('nama_perusahaan', 'like', '%' . $filter['cari'] . '%');
                        })
                        ->orWhere('muatan', 'like', '%' . $filter['cari'] . '%')
                        ->orWhereHas('armada', function ($query3) use ($filter) {
                            $query3->where('nopol', 'like', '%' . $filter['cari'] . '%');
                        })
                        ->orWhereHas('sopir', function ($query3) use ($filter) {
                            $query3->where('nama', 'like', '%' . $filter['cari'] . '%');
                        })
                        ->orWhere("asal", "like", "%" . $filter['cari'] . "%")
                        ->orWhere("tujuan", "like", "%" . $filter['cari'] . "%")
                        ->orWhere('tanggal_awal', 'like', '%' . $filter['cari'] . '%')
                        ->orWhere('tanggal_akhir', 'like', '%' . $filter['cari'] . '%')
                        ->orWhere('nomor_sj', 'like', '%' . $filter['cari'] . '%')
                        ->orWhere('nomor_po', 'like', '%' . $filter['cari'] . '%')
                        ->orWhere('nomor_do', 'like', '%' . $filter['cari'] . '%')
                        ->orWhere('catatan_surat_jalan', 'like', '%' . $filter['cari'] . '%')
                        ->orWhere("harga_order", "like", "%" . $filter['cari'] . "%");
                });
            })
            ->when($filter['ppn'] == 'ada', function ($query) use ($filter) {
                $query->whereNotNull("ppn");
            })
            ->when($filter['ppn'] == 'tidak_ada', function ($query) use ($filter) {
                $query->whereNull("ppn");
            })
            ->select(DB::raw(
                "CASE
            WHEN transaksi_order.status_kendaraan = 'Sendiri'
             THEN 
              (select SUM(nominal) FROM master_mutasi WHERE master_mutasi.transaksi_order_id = transaksi_order.id AND master_mutasi.jenis_transaksi = 'order') 
             ELSE 
              (select SUM(nominal) FROM master_mutasi WHERE master_mutasi.transaksi_order_id = transaksi_order.id AND master_mutasi.jenis_transaksi = 'jual')
            END
           AS total_terbayar
         ,CASE 
            WHEN transaksi_order.status_kendaraan = 'Sendiri'
             THEN
               IF(transaksi_order.harga_order_bersih <= (select SUM(nominal) FROM master_mutasi WHERE master_mutasi.transaksi_order_id = transaksi_order.id AND master_mutasi.jenis_transaksi = 'order'),'lunas','belum_lunas') 
             ELSE
               IF(transaksi_order.harga_jual_bersih <= (select SUM(nominal) FROM master_mutasi WHERE master_mutasi.transaksi_order_id = transaksi_order.id AND master_mutasi.jenis_transaksi = 'jual'),'lunas','belum_lunas')
             END 
           AS status_lunas,transaksi_order.*"
            ))
            ->when($filter['status_lunas'] == 'lunas', function ($q) {
                $q->whereRaw(
                    "IF(true, transaksi_order.harga_order_bersih <= (select SUM(nominal) FROM master_mutasi WHERE master_mutasi.transaksi_order_id = transaksi_order.id AND master_mutasi.jenis_transaksi = 'order')
                ,transaksi_order.harga_jual_bersih <= (select SUM(nominal) FROM master_mutasi WHERE master_mutasi.transaksi_order_id = transaksi_order.id AND master_mutasi.jenis_transaksi = 'jual'))"
                );
            })
            ->when($filter['status_lunas'] == 'belum_lunas', function ($q) {
                $q->whereRaw(
                    "IF(true, transaksi_order.harga_order_bersih > (select SUM(nominal) FROM master_mutasi WHERE master_mutasi.transaksi_order_id = transaksi_order.id AND master_mutasi.jenis_transaksi = 'order')
                ,transaksi_order.harga_jual_bersih > (select SUM(nominal) FROM master_mutasi WHERE master_mutasi.transaksi_order_id = transaksi_order.id AND master_mutasi.jenis_transaksi = 'jual'))"
                );
            });
        $data = $data->orderByRaw("tanggal_awal DESC," . (DB::raw("CAST(SUBSTRING_INDEX(no_transaksi, '.', -1) AS UNSIGNED) DESC")));
        $sort = "no_transaksi DESC";
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;
        // dd($data->paginate($itemPerPage)->appends("sort", $sort));
        return ($data->paginate($itemPerPage)->appends("sort", $sort));
    }

    /**
     * Get last order by tahun
     * @param $tanggal string
     * @return object
     */
    public function getLastOrderByTahun($tahun)
    {
        $data = $this->query()
            ->where(DB::raw("SUBSTRING(SUBSTRING_INDEX(no_transaksi, '.', -2), 1, 4)"), "=", $tahun)
            ->orderByRaw(DB::raw("CAST(SUBSTRING_INDEX(no_transaksi, '.', -1) AS UNSIGNED) DESC"))
            ->select(['no_transaksi', DB::raw("SUBSTRING(SUBSTRING_INDEX(no_transaksi, '.', -2), 1, 8) as tanggal")])->first();
        return $data;
    }


    /**
     * Kalkulasi sisa uang jalan
     * @param mixed $query
     * @param \App\Enums\TipeKalkulasiSisaUangJalanEnum|null $type Hutang atau null
     * @return float
     */
    public static function kalkulasSisaUangJalan($query, TipeKalkulasiSisaUangJalanEnum|null $type = null): float
    {
        // Default type to ALL if null
        $type = $type ?? TipeKalkulasiSisaUangJalanEnum::ALL;

        // Define the callback based on the calculation type
        $callback = match ($type) {
            TipeKalkulasiSisaUangJalanEnum::ALL => fn($value) => $value,
            TipeKalkulasiSisaUangJalanEnum::HUTANG => fn($value) => $value > 0 ? 0 : $value,
            default => throw new InvalidArgumentException('Invalid calculation type provided'),
        };

        // Clone the current query to prevent affecting the original query
        $clone = clone $query;

        // Calculate total using the callback function
        return $clone
            ->withSum('mutasi_jalan as total_pembayaran', 'nominal')
            ->get()
            ->sum(function ($order) use ($callback) {
                $value = $order->uang_jalan_bersih - $order->total_pembayaran;
                return $callback($value);
            });
    }
}
