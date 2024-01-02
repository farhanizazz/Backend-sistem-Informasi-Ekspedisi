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
    /**
     * Handle the MutasiModel "created" event.
     *
     * @param  \App\Models\MutasiModel  $mutasiModel
     * @return void
     */
    public function created(MutasiModel $mutasiModel)
    {
        $this->rekeningModel->where('id',$mutasiModel->master_rekening_id)->update([
            'saldo' => $this->rekeningModel->where('id',$mutasiModel->master_rekening_id)->first()->saldo + $mutasiModel->nominal
        ]);
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
        $this->rekeningModel->where('id',$mutasiModel->master_rekening_id)->update([
            'saldo' => $this->rekeningModel->where('id',$mutasiModel->master_rekening_id)->first()->saldo - $mutasiModel->nominal
        ]);
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
