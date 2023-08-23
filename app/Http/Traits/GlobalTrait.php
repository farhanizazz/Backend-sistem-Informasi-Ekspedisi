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
}