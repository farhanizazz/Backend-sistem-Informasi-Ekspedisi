<?php

namespace App\Helpers\Master;

class ArmadaHelper{
  protected $armadaModel;
  private $TIDAKAKTIF = 'nonaktif';
  private $AKTIF = 'aktif'; 

  public function __construct()
  {
    $this->armadaModel = new \App\Models\Master\ArmadaModel();
  }

  public function getAll(){
    return $this->armadaModel->all();
  }

  public function cekTanggalBerlaku($armada){
    if ($armada['tgl_stnk'] < date('Y-m-d')) {
        $this->update($armada['id'], ['status_stnk' => $this->TIDAKAKTIF]);
    }

    if($armada['tgl_uji_kir'] < date('Y-m-d')){
        $this->update($armada['id'], ['status_uji_kir' => $this->TIDAKAKTIF]);
    }
  }

  public function update($id, $data){
    return $this->armadaModel->find($id)->update($data);
  }


    
}