<?php

namespace App\Observers;

use App\Enums\JenisTransaksiMutasiEnum;
use App\Models\Master\MutasiModel;
use App\Models\Master\RekeningModel;

class MutasiObserver
{
    private $rekeningModel;
    public function __construct()
    {
        $this->rekeningModel = new RekeningModel();
    }


    public function creating(MutasiModel $mutasiModel)
    {
        $mutasiModel->created_by = auth()->user()->id ?? null;
    }

    /**
     * Handle the MutasiModel "created" event.
     *
     * @param  \App\Models\MutasiModel  $mutasiModel
     * @return void
     */
    public function created(MutasiModel $mutasiModel)
    {
        if ($mutasiModel->jenis_transaksi->value == JenisTransaksiMutasiEnum::ORDER->value || $mutasiModel->jenis_transaksi->value == JenisTransaksiMutasiEnum::PEMASUKAN->value) {
            $this->rekeningModel->where('id',$mutasiModel->master_rekening_id)->update([
                'saldo' => $this->rekeningModel->where('id',$mutasiModel->master_rekening_id)->first()->saldo + $mutasiModel->nominal
            ]);
        }else{
            $this->rekeningModel->where('id',$mutasiModel->master_rekening_id)->update([
                'saldo' => $this->rekeningModel->where('id',$mutasiModel->master_rekening_id)->first()->saldo - $mutasiModel->nominal
            ]);
        }
    }

    /**
     * Handle the MutasiModel "updated" event.
     *
     * @param  \App\Models\MutasiModel  $mutasiModel
     * @return void
     */
    public function updated(MutasiModel $mutasiModel)
    {
        // Update saldo rekening, dengan mengambil nominal mutasi sebelumnya dan menguranginya dengan nominal mutasi yang baru
        $mutasiModel->getOriginal('nominal');
        $mutasiModel->getOriginal('master_rekening_id');
        $mutasiModel->getOriginal('jenis_transaksi');
        if ($mutasiModel->jenis_transaksi->value == JenisTransaksiMutasiEnum::ORDER->value || $mutasiModel->jenis_transaksi->value == JenisTransaksiMutasiEnum::PEMASUKAN->value) {
            $this->rekeningModel->where('id',$mutasiModel->master_rekening_id)->update([
                'saldo' => $this->rekeningModel->where('id',$mutasiModel->master_rekening_id)->first()->saldo + $mutasiModel->nominal - $mutasiModel->getOriginal('nominal')
            ]);
        }else{
            $this->rekeningModel->where('id',$mutasiModel->master_rekening_id)->update([
                'saldo' => $this->rekeningModel->where('id',$mutasiModel->master_rekening_id)->first()->saldo - $mutasiModel->nominal + $mutasiModel->getOriginal('nominal')
            ]);
        }
        

    }

    /**
     * Handle the MutasiModel "deleted" event.
     *
     * @param  \App\Models\MutasiModel  $mutasiModel
     * @return void
     */
    public function deleted(MutasiModel $mutasiModel)
    {
        if($mutasiModel->jenis_transaksi->value == "uang_jalan" || $mutasiModel->jenis_transaksi->value == JenisTransaksiMutasiEnum::PENGELUARAN->value) {
            $this->rekeningModel->where('id',$mutasiModel->master_rekening_id)->update([
                'saldo' => $this->rekeningModel->where('id',$mutasiModel->master_rekening_id)->first()->saldo + $mutasiModel->nominal
            ]);
        } else {
            $this->rekeningModel->where('id',$mutasiModel->master_rekening_id)->update([
                'saldo' => $this->rekeningModel->where('id',$mutasiModel->master_rekening_id)->first()->saldo - $mutasiModel->nominal
            ]);
        }
    }

    /**
     * Handle the MutasiModel "restored" event.
     *
     * @param  \App\Models\MutasiModel  $mutasiModel
     * @return void
     */
    public function restored(MutasiModel $mutasiModel)
    {
        //
    }

    /**
     * Handle the MutasiModel "force deleted" event.
     *
     * @param  \App\Models\MutasiModel  $mutasiModel
     * @return void
     */
    public function forceDeleted(MutasiModel $mutasiModel)
    {
        //
    }
}
