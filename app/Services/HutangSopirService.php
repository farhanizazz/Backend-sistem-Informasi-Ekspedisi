<?php

namespace App\Services;

use App\Repositories\Contracts\HutangSopirRepositoryInterface;
use Illuminate\Http\Request;

class HutangSopirService{
  private $hutangSopirRepository;

  public function __construct(HutangSopirRepositoryInterface $hutangSopirRepository)
  {
    $this->hutangSopirRepository = $hutangSopirRepository;
  }

  public function getPaginate(Request $request)
  {
    $itemPerPage = $request->get('itemPerPage') ?? 10;
    return $this->hutangSopirRepository->getPaginate($itemPerPage);
  }

  public function getTotalHutangSopir(Request $request)
  {
    $itemPerPage = $request->get('itemPerPage') ?? 10;
    return $this->hutangSopirRepository->getJumlahHutangSopir($itemPerPage);
  }

  public function getTotalHutangSopirById($id)
  {
    return $this->hutangSopirRepository->getJumalhHutangSopirById($id);
  }

  public function getHutangSopirById($id,Request $request)
  {
    $itemPerPage = $request->get('itemPerPage') ?? 10;
    $nama = $request->get('nama') ?? '';
    $nominal = $request->get('nominal') ?? '';
    $tanggal = $request->get('tanggal') ?? '';
    $listHutang = $this->hutangSopirRepository->getHutangSopirById($id, $itemPerPage, $nama, $nominal, $tanggal);
    $sopir = $this->getTotalHutangSopirById($id);
    return [
      'list' => $listHutang,
      'sopir' => $sopir,
    ];
  }


}