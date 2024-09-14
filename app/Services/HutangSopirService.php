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
    $search = $request->get('search') ?? '';
    return $this->hutangSopirRepository->getJumlahHutangSopir($itemPerPage,$search);
  }

  public function getTotalHutangSopirById($id)
  {
    return $this->hutangSopirRepository->getJumalhHutangSopirById($id);
  }

  public function getHutangSopirById($id,Request $request)
  {
    $itemPerPage = $request->get('itemPerPage') ?? 10;
    $search = $request->get('search') ?? '';
    $orderBy = $request->get('orderBy') ?? 'created_at DESC';
    $listHutang = $this->hutangSopirRepository->getHutangSopirById($id, $itemPerPage,$search,$orderBy);
    $sopir = $this->getTotalHutangSopirById($id);
    return [
      'list' => $listHutang,
      'sopir' => $sopir,
    ];
  }


}