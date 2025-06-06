<?php

namespace App\Helpers\Laporan\V2;

use App\Models\Transaksi\OrderModel;

class ArmadaRugiLabaPajakHelper extends ArmadaRugiLabaHelper
{
  public function getResources($subQueryPemasukan = null)
  {
    // Pemasukan
    $subQueryPemasukan = OrderModel::query()
      ->where('total_pajak', '>', 0);

    return parent::getResources($subQueryPemasukan);
  }
}
