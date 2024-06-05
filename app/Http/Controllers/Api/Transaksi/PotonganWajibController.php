<?php

namespace App\Http\Controllers\Api\Transaksi;

use App\Http\Controllers\Controller;
use App\Helpers\Transaksi\OrderHelper;
use App\Http\Resources\PotonganWajib\PotonganWajibCollection;
use App\Models\Transaksi\OrderModel;
use Illuminate\Http\Request;

class PotonganWajibController extends Controller
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
            'data' => new PotonganWajibCollection($this->orderModel->getIncome())
        ]);
    }
}
