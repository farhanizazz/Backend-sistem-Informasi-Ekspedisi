<?php

namespace App\Http\Controllers\Api\Transaksi;


use App\Helpers\Transaksi\OrderHelper;

use App\Models\Transaksi\OrderModel;
use App\Http\Controllers\Controller;
use App\Http\Resources\Income\IncomeCollection;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    //
    private $orderHelper, $orderModel;
    public function __construct()
    {
        $this->orderHelper = new OrderHelper();
        $this->orderModel = new OrderModel();
    }

    public function index(Request $request)
    {
        
        return response()->json([
            'status' => 'success',
            'data' => new IncomeCollection($this->orderModel->getIncome())
        ]);
    }

}
