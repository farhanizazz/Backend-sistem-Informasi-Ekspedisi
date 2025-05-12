<?php

namespace App\Http\Controllers\Api\Transaksi;

use App\Helpers\Transaksi\OrderHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest\CreateRequest;
use App\Http\Resources\Order\OrderCollection;
use App\Http\Resources\Order\OrderResource;
use App\Models\Master\MutasiModel;
use App\Models\Transaksi\OrderModel;
use App\Models\Transaksi\TransaksiTagihanDetModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    //
    private $orderHelper, $orderModel;
    public function __construct()
    {
        $this->orderHelper = new OrderHelper();
        $this->orderModel = new OrderModel();
    }

    /**
     * @OA\Get(
     * path="/api/transaksi/order",
     * summary="Get data Transaksi/order",
     * tags={"Transaksi Order"},
     * @OA\Parameter(
     *  name="search",
     *  description="Kata kunci untuk mencari data Transaksi order",
     *  required=false,
     *  in="query",
     * ),
     * @OA\Response(
     *  response=200,
     *  description="Data Transaksi order berhasil ditemukan"
     * ),
     * )
     */
    public function index(Request $request)
    {
        $filter = [
            "status_kendaraan" => $request->status_kendaraan ?? null,
            "cari"              => $request->cari ?? null,
            "nama_penyewa"      => $request->nama_penyewa ?? null,
            "status_lunas"      => $request->status_lunas ?? null,
            "ppn"               => $request->ppn ?? null,
            "biaya_lain"        => $request->biaya_lain ?? null,
        ];
        return response()->json([
            'status' => 'success',
            'data' => new OrderCollection($this->orderModel->index($filter))
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
            'message' => $result['message'],
            'data' => $result['data']
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
            'message' => $result['message'],
            'data' => $result['data']
        ]);
    }

    public function destroy($id, Request $request)
    {
        try {
            //code...
            DB::beginTransaction();
            $order = OrderModel::find($id);
            if ($order) {
                if ($request->force == "true") {
                    $order = OrderModel::where('id', $id)->get();
                    TransaksiTagihanDetModel::whereIn('transaksi_order_id', $order->pluck('id'))->delete();
                    $mutasis = MutasiModel::whereIn('transaksi_order_id', $order->pluck('id'))->get();
                    foreach ($mutasis as $key => $mutasi) {
                        $mutasi->delete();
                    }
                    OrderModel::where('id', $id)->delete();
                } else {
                    $order->delete();
                }

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil dihapus'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            if ($th->getCode() == 23000) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'Data ini tidak dapat diubah karena sedang digunakan di tabel lain.'
                    ]
                );
            }

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
