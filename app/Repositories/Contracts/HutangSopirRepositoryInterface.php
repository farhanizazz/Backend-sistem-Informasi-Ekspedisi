<?php

namespace App\Repositories\Contracts;
interface HutangSopirRepositoryInterface
{
    public function getAll();
    public function getPaginate($itemPerPage);
    public function getJumlahHutangSopir($itemPerPage);
    public function getHutangSopirById($id, $itemPerPage,$nama,$nominal,$tanggal);
    public function getJumalhHutangSopirById($id);
    public function getById($id);
    public function create($data);
    public function update($data, $id);
    public function delete($id);
}