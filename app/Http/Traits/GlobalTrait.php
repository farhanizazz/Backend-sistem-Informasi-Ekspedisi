<?php
namespace App\Http\Traits;
trait GlobalTrait
{
    public function templateAkses(){
      return [
        'master_armada' => [
          'view'    => false,
          'create'  => false,
          'edit'    => false,
          'delete'  => false,
        ],
        'master_penyewa' => [
          'view'    => false,
          'create'  => false,
          'edit'    => false,
          'delete'  => false,
        ],
        'master_rekening' => [
          'view'    => false,
          'create'  => false,
          'edit'    => false,
          'delete'  => false,
        ],
        'master_user' => [
          'view'    => false,
          'create'  => false,
          'edit'    => false,
          'delete'  => false,
        ],
        'master_sopir' => [
          'view'    => false,
          'create'  => false,
          'edit'    => false,
          'delete'  => false,
        ],
      ];
    }

    public function tanggalKeLisan($tanggal){
     // Tanggal peringatan
      $peringatan = strtotime("$tanggal 23:59:00"); // Ganti dengan tanggal peringatan yang diinginkan

      // Waktu saat ini
      $waktu_saat_ini = time();

      // Menghitung selisih dalam detik antara tanggal peringatan dan waktu saat ini
      $selisih_detik = $peringatan - $waktu_saat_ini;

      if ($selisih_detik <= 0) {
          $selisih_hari = abs(floor($selisih_detik / (60 * 60 * 24)));
          return "telah terlewati $selisih_hari hari.";
      } else {
          $selisih_hari = floor($selisih_detik / (60 * 60 * 24));
          $selisih_jam = floor(($selisih_detik % (60 * 60 * 24)) / (60 * 60));
          $selisih_menit = floor(($selisih_detik % (60 * 60)) / 60);

          if ($selisih_hari >= 1) {
              return "$selisih_hari hari lagi.";
          } elseif ($selisih_jam >= 1) {
              return "$selisih_jam jam lagi.";
          } else {
              return "$selisih_menit menit lagi.";
          }
      }
    }

    public function getSifatRekening($sifat){
      if($sifat == config('global.sifat_rekening.plus')){
        return 1;
      }else if($sifat == config('global.sifat_rekening.minus')){
        return -1;
      }
      return 1;
    }

    public function checkArrayIssetOnRequest($request, array $requestRequired,string $request_name)
    {
      $is_error = false;
      $errArr = [];
        foreach ($requestRequired as $key => $value) {
          foreach ($request as $requestKey => $requestValue) {
            # code...
            if (!isset($requestValue[$value])) {
                $message = "";
                foreach ($requestRequired as $requiredKey => $requiredValue) {
                    $message .= $requiredValue . ", "; 
                }
                $is_error = true;
                $errArr = [
                  'status' => false,
                  'message' => "Field " . $message . " tidak ada pada request " . $request_name
              ];
              break;  
            }
          }
        }
        if($is_error){
          return $errArr;
        }
        return [
            'status' => true,
            'message' => "Field " . implode(", ", $requestRequired) . " ada pada request " . $request_name
        ];
    }
}