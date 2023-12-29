<?php

namespace App\Helpers\Master;

use App\Models\Master\TambahanModel;

// class ArmadaHelper{
//     protected $tambahanModel;
//     private $TIDAKAKTIF = 'nonaktif';
//     private $AKTIF = 'aktif';

//     public function __construct()
//     {
//       $this->tambahanModel = new \App\Models\Master\TambahanModel();
//     }

//     public function getAll(){
//       return $this->tambahanModel->all();
//     }

//     public function hitungTotal($tambahan){

//         $total=$tambahan['total'] = $tambahan['biaya_kuli']+$tambahan['biaya_akomodasi']+$tambahan['claim']+$tambahan['biaya_tol']+$tambahan['brg_rusak'];
//         $this->tambahanModel->createData(['total'=>$total]);
//     }
//     public function create($data){
//         return $this->armadaModel->createData($data);
//     }

// }
