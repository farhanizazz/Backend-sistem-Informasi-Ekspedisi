<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\PenyewaRequest\CreateRequest;
use App\Http\Requests\PenyewaRequest\UpdateRequest;
use App\Models\Master\MutasiModel;
use App\Models\Master\PenyewaModel;
use App\Models\Transaksi\OrderModel;
use App\Models\Transaksi\TransaksiTagihanDetModel;
use App\Models\Transaksi\TransaksiTagihanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TransaksiTagihan;
use TransaksiTagihanDet;

class PenyewaController extends Controller
{

    private $penyewaModel;

    public function __construct()
    {
        $this->penyewaModel = new PenyewaModel();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(
            [
                'status' => 'success',
                'data' => $this->penyewaModel->all()
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
        $result = $this->penyewaModel->create($request->all());

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Data berhasil ditambahkan',
                'data' => $result
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            //code...
            return response()->json(
                [
                    'status' => 'success',
                    'data' => $this->penyewaModel->findOrFail($id)
                ]
            );
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ]
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            //code...
            if (isset($request->validator) && $request->validator->fails()) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => $request->validator->errors()
                    ]
                );
            }

            $response = $this->penyewaModel->findOrFail($id)->update($request->all());
            if (!$response) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'Data gagal diubah'
                    ]
                );
            }
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data berhasil diubah',
                    'data' => $this->penyewaModel->findOrFail($id)
                ]
            );
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        try {
            DB::beginTransaction();
                if($request->force == "true"){
                    $order = OrderModel::where('m_penyewa_id', $id)->get();
                    TransaksiTagihanDetModel::whereIn('transaksi_order_id', $order->pluck('id'))->delete();
                    $mutasis = MutasiModel::whereIn('transaksi_order_id', $order->pluck('id'))->get();
                    foreach ($mutasis as $key => $mutasi) {
                        $mutasi->delete();
                    }
                    OrderModel::where('m_penyewa_id', $id)->delete();
                    TransaksiTagihanModel::where('m_penyewa_id', $id)->delete();
                    $this->penyewaModel->findOrfail($id)->delete();
                }else{
                    $this->penyewaModel->findOrfail($id)->delete();
                }
            DB::commit();
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data berhasil dihapus'
                ]
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            if ($th->getCode() == 23000) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'Data ini tidak dapat diubah karena sedang digunakan di tabel lain.'
                    ]
                );
            }

            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ]
            );
        }
    }
}
