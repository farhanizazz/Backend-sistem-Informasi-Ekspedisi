<?php

namespace App\Http\Controllers\Api\Transaksi;

use App\Helpers\Transaksi\OrderHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest\CreateRequest;
use App\Http\Resources\Order\OrderCollection;
use App\Http\Resources\Order\OrderResource;
use App\Models\Transaksi\OrderModel;
use Illuminate\Http\Request;

class OrderController extends Controller
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
        $filter = [
            "status_kendaraan" => $request->status_kendaraan ?? null 
        ];
        return response()->json([
            'status' => 'success',
            'data' => new OrderCollection($this->orderModel->when($filter['status_kendaraan'],function($query) use($filter){
                $query->where("status_kendaraan", $filter['status_kendaraan']);
            })->with(['penyewa', 'armada', 'sopir', 'subkon'])->get())
        ]);
    }

    public function store(CreateRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $request->validator->errors()
                ]
            );
        }
        $result =  $this->orderHelper->create($request);
        if (!$result['status']) {
            return response()->json([
                'status' => 'error',
                'message' => $result['message'],
                'dev'   => $result['dev']
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => $result['message']
        ]);
    }

    public function update(CreateRequest $request, $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $request->validator->errors()
                ]
            );
        }
        $result =  $this->orderHelper->update($request, $id);
        if (!$result['status']) {
            return response()->json([
                'status' => 'error',
                'message' => $result['message'],
                'dev'   => $result['dev']
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => $result['message']
        ]);
    }

    public function destroy($id)
    {
        try {
            //code...
            $order = OrderModel::find($id);

            if ($order) {
                // Delete the associated MutasiModel
                $order->mutasi()->delete();
        
                // Then delete the OrderModel
                $order->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function show($id)
    {
        try {
            //code...
            $result =  $this->orderModel->with(['penyewa', 'armada', 'sopir', 'subkon'])->findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => new OrderResource($result)
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
}
