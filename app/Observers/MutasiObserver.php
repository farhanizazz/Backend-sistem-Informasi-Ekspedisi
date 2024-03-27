<?php

namespace App\Observers;

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
        if ($mutasiModel->jenis_transaksi == "order") {
            # code...
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
        //
    }

    /**
     * Handle the MutasiModel "deleted" event.
     *
     * @param  \App\Models\MutasiModel  $mutasiModel
     * @return void
     */
    public function deleted(MutasiModel $mutasiModel)
    {
        if($mutasiModel->jenis_transaksi == "uang_jalan") {
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
