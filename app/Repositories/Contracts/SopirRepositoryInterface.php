<?php
namespace App\Repositories\Contracts;
interface SopirRepositoryInterface
{
    public function getAll();
    public function getPaginate($itemPerPage);
    public function getById($id);
    public function create($data);
    public function update($data, $id);
    public function delete($id);
}