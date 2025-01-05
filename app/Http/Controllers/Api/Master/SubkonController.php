<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubkonRequest\CreateRequest;
use App\Http\Requests\SubkonRequest\UpdateRequest;
use App\Models\Master\MutasiModel;
use App\Models\Master\SubkonModel;
use App\Models\Transaksi\OrderModel;
use App\Models\Transaksi\TransaksiTagihanDetModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubkonController extends Controller
{
    private $subkonModel;

    public function __construct()
    {
        $this->subkonModel = new SubkonModel();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json([
            'status' => 'success',
            'data' => $this->subkonModel->all()
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
            return response()->json([
                    'status' => 'error',
                    'message' => $request->validator->errors()
                ]
            );
        }
        $result = $this->subkonModel->create($request->all());

        return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil ditambahkan',
                'data' => $result
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

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
            return response()->json([
                    'status' => 'success',
                    'data' => $this->subkonModel->findOrFail($id)
                ]
            );
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ]
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            return response()->json([
                    'status' => 'error',
                    'message' => $request->validator->errors()->all()
                ]
            );
        }

        $response = $this->subkonModel->findOrFail($id)->update($request->all());
        if (!$response) {
            return response()->json([
                    'status' => 'error',
                    'message' => 'Data gagal diubah'
                ]
            );
        }
        return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diubah',
                'data' => $this->subkonModel->findOrFail($id)
            ]
        );
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
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
                $order = OrderModel::where('m_subkon_id', $id)->get();
                TransaksiTagihanDetModel::whereIn('transaksi_order_id', $order->pluck('id'))->delete();
                MutasiModel::whereIn('transaksi_order_id', $order->pluck('id'))->delete();
                OrderModel::where('m_subkon_id', $id)->delete();

                $this->subkonModel->findOrFail($id)->forceDelete();
            }else{
                $this->subkonModel->findOrFail($id)->delete();
            }
            DB::commit();
            return response()->json([
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


            return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ]
            );
        }
    }

}
