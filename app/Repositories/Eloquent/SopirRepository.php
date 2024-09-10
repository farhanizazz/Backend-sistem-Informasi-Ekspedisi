<?php

namespace App\Repositories\Eloquent;

use App\Models\Master\SopirModel;
use App\Repositories\Contracts\SopirRepositoryInterface;

class SopirRepository implements SopirRepositoryInterface
{
    protected $model;

    public function __construct(SopirModel $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->get();
    }

    public function getPaginate($itemPerPage)
    {
        return $this->model->paginate($itemPerPage);
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($data, $id)
    {
        return $this->model->find($id)->update($data);
    }

    public function delete($id)
    {
        return $this->model->find($id)->delete();
    }
}