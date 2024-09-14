<?php

namespace App\Repositories\Eloquent;

use App\Models\Transaksi\HutangSopirModel;
use App\Repositories\Contracts\HutangSopirRepositoryInterface;
use Illuminate\Support\Facades\DB;

class HutangSopirRepository implements HutangSopirRepositoryInterface
{
	protected $model;

	public function __construct(HutangSopirModel $model)
	{
		$this->model = $model;
	}

	public function getAll()
	{
		// implementation goes here
	}

	public function getPaginate($itemPerPage)
	{
		// implementation goes here
		return $this->model->with('master_sopir:id,nama')->paginate($itemPerPage);
	}

	public function getById($id)
	{
		// implementation goes here
	}

	public function create($data)
	{
		// implementation goes here
	}

	public function update($id, $data)
	{
		// implementation goes here
	}

	public function delete($id)
	{
		// implementation goes here
	}

	public function getJumlahHutangSopir($itemPerPage, $search)
	{
		// implementation goes here
		return $this->model
			->with(['master_sopir:id,nama'])
			->whereHas('master_sopir', function ($query) use ($search) {
				$query->where('nama', 'like', '%' . $search . '%');
			})->orWhereRaw('(SELECT SUM(nominal_trans) FROM hutang_sopir hs WHERE hs.master_sopir_id = hutang_sopir.master_sopir_id) = ?', $search)
			->selectRaw('master_sopir_id,sum(nominal_trans) as total_hutang')
			->groupBy('master_sopir_id')
			->paginate($itemPerPage);
	}

	public function getJumalhHutangSopirById($id)
	{
		return $this->model
			->with('master_sopir:id,nama')
			->where('master_sopir_id', $id)->selectRaw('master_sopir_id,sum(nominal_trans) as total_hutang')->first();
	}

	public function getHutangSopirById($id, $itemPerPage, $search, $orderBy = 'tgl_transaksi DESC')
	{
		return $this->model->where('master_sopir_id', $id)
			->when($search, function ($query) use ($search) {
				$query->where('nominal_trans', 'like', '%' . $search . '%')
					->where('tgl_transaksi', 'like', '%' . $search . '%');
			})->orderByRaw($orderBy)->paginate($itemPerPage);
	}
}
