<?php 
namespace App\Repositories\Eloquent;

use App\Models\Transaksi\HutangSopirModel;
use App\Repositories\Contracts\HutangSopirRepositoryInterface;

class HutangSopirRepository implements HutangSopirRepositoryInterface
{
  protected $model;

  public function __construct(HutangSopirModel $model)
  {
    $this->model = $model;
  }

	public function getPaginate()
	{
		// implementation goes here
	}

	public function getById($id)
	{
		// implementation goes here
	}
  
	public function create($data)
	{
		// implementation goes here
	}

	public function update($id,$data)
	{
		// implementation goes here
	}

	public function delete($id)
	{
		// implementation goes here
	}

	public function getJumlahHutangSopir()
	{
		// implementation goes here
    return $this->model
      ->with('master_sopir:id,nama')  
      ->selectRaw('master_sopir_id,sum(nominal_trans) as total_hutang')
      ->groupBy('master_sopir_id')
      ->get();
	}

  public function getJumalhHutangSopirById($id)
  {
    return $this->model
      ->with('master_sopir:id,nama')
      ->where('master_sopir_id',$id)->selectRaw('master_sopir_id,sum(nominal_trans) as total_hutang')->first();
  }
}