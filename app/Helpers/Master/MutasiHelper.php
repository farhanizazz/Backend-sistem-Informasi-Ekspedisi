<?php
namespace App\Helpers\Master;

use App\Models\Master\MutasiModel;

class MutasiHelper{

  private $mutasiModel;
  public function __construct()
  {
    $this->mutasiModel = new MutasiModel();
  }

  public function create($payload){
    try {
      $this->mutasiModel->create($payload);
      return [
        'status' => true,
        'message' => 'Data berhasil ditambahkan'
      ];
    } catch (\Throwable $th) {
      return [
        'status' => false,
        'message' => 'Data gagal ditambahkan',
        'dev' => $th->getMessage()
      ];
    }
  }
}