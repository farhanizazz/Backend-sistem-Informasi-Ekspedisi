<?php

namespace App\Services;

use App\Repositories\Contracts\SopirRepositoryInterface;
use Illuminate\Http\Request;

class SopirService
{
    protected $sopirRepository;
    public function __construct(SopirRepositoryInterface $sopirRepository)
    {
        $this->sopirRepository = $sopirRepository;
    }

    public function getAll()
    {
        return $this->sopirRepository->getAll();
    }

    public function getPaginate(Request $request)
    {
        $itemPerPage = $request->input('itemPerPage') ?? 10;
        return $this->sopirRepository->getPaginate($itemPerPage);
    }
    public function getById($id)
    {
        return $this->sopirRepository->getById($id);
    }
    public function create($data)
    {
        return $this->sopirRepository->create($data);
    }
    public function update($data, $id)
    {
        return $this->sopirRepository->update($data, $id);
    }
    public function delete($id)
    {
        return $this->sopirRepository->delete($id);
    }
}