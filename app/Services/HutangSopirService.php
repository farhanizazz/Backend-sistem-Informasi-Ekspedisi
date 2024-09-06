<?php

namespace App\Services;

use App\Repositories\Contracts\HutangSopirRepositoryInterface;

class HutangSopirService{
  private $hutangSopirRepository;

  public function __construct(HutangSopirRepositoryInterface $hutangSopirRepository)
  {
    $this->hutangSopirRepository = $hutangSopirRepository;
  }

  public function getTotalHutangSopir()
  {
    return $this->hutangSopirRepository->getJumlahHutangSopir();
  }

  public function getTotalHutangSopirById($id)
  {
    return $this->hutangSopirRepository->getJumalhHutangSopirById($id);
  }


}