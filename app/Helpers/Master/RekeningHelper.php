<?php

namespace App\Helpers\Master;

use App\Models\Master\RekeningModel;

// class ArmadaHelper{
//     protected $rekeningModel;
//     private $TIDAKAKTIF = 'nonaktif';
//     private $AKTIF = 'aktif';

//     public function __construct()
//     {
//       $this->rekeningModel = new \App\Models\Master\RekeningModel();
//     }

//     public function getAll(){
//       return $this->rekeningModel->all();
//     }

//     public function hitungTotal($rekening){

//         $total=$rekening['total'] = $rekening['biaya_kuli']+$rekening['biaya_akomodasi']+$rekening['claim']+$rekening['biaya_tol']+$rekening['brg_rusak'];
//         $this->rekeningModel->createData(['total'=>$total]);
//     }
//     public function create($data){
//         return $this->armadaModel->createData($data);
//     }

// }
