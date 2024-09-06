<?php

namespace App\Repositories\Contracts;
interface HutangSopirRepositoryInterface
{
    public function getPaginate();
    public function getJumlahHutangSopir();
    public function getJumalhHutangSopirById($id);
    public function getById($id);
    public function create($data);
    public function update($data, $id);
    public function delete($id);
}