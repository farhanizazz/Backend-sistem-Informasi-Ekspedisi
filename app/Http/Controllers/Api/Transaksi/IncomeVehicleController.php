<?php

namespace App\Http\Controllers\Api\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\Transaksi\OrderHelper;

use App\Models\Transaksi\OrderModel;
use App\Http\Resources\IncomeVehicle\IncomeVehicleCollection;

class IncomeVehicleController extends Controller

{
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
            'data' => new IncomeVehicleCollection($this->orderModel->getIncome())
        ]);
    }

}