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
}