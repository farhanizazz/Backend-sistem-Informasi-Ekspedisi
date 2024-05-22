<?php 
  namespace App\Helpers;

use App\Http\Traits\GlobalTrait;
use App\Models\Master\ArmadaModel;
use App\Models\Notifikasi;


  class NotifikasiHelper{
    use GlobalTrait;

    private $armadaModel;

    public function __construct()
    {
      $this->armadaModel = new ArmadaModel();
    }


    public function getReminderPajak(){
      try {
        //code...

      $min7Hari = date('Y-m-d', strtotime('+7 days'));

      $pajakStnk = $this->armadaModel->getArmadaPajakStnkByTime($min7Hari);
      $pajakKir = $this->armadaModel->getArmadaPajakKirByTime($min7Hari);

      $data = [];
      foreach ($pajakStnk as $key => $value) {
        $status = date('Y-m-d') > $value->tgl_stnk ? 'telah' : 'akan';
        $data[] = [
          'id' => $value->id,
          'jenis' => 'STNK',
          'nopol' => $value->nopol,
          'tgl' => $value->tgl_stnk,
          'selisih' => $this->tanggalKeLisan($value->tgl_stnk),
          'message' => 'Armada dengan nopol '.$value->nopol." $status jatuh tempo pajak STNK pada tanggal ".date('d-m-Y', strtotime($value->tgl_stnk)).'. Segera lakukan pembayaran pajak STNK.',
        ];
      }

      foreach ($pajakKir as $key => $value) {
        $data[] = [
          'id' => $value->id,
          'jenis' => 'KIR',
          'nopol' => $value->nopol,
          'tgl' => $value->tgl_uji_kir,
          'selisih' => $this->tanggalKeLisan($value->tgl_uji_kir),
          'message' => 'Armada dengan nopol '.$value->nopol." $status jatuh tempo uji KIR pada tanggal ".date('d-m-Y',strtotime($value->tgl_uji_kir)).'. Segera lakukan pembayaran uji KIR.',
        ];
      }

      return [
        'status' => 'success',
        'data' => $data,
      ];
    } catch (\Throwable $th) {
      //throw $th;
      return [
        'status' => 'error',
        'message' => $th->getMessage(),
      ];
    }
    }
  }