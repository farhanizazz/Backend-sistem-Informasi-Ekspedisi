<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\RekeningRequest\CreateRequest;
use App\Http\Requests\RekeningRequest\UpdateRequest;
use App\Models\Master\MutasiModel;
use App\Models\Master\RekeningModel;
use App\Models\Transaksi\ServisMutasiModel;
use App\Models\Transaksi\TransaksiTagihanDetModel;
use App\Models\Transaksi\TransaksiTagihanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TransaksiTagihan;

class RekeningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private  $rekeningModel;
    public function __construct()
    {
        $this->rekeningModel = new RekeningModel();
    }

    /**
     * @OA\Get(
     * path="/api/master/rekening",
     * summary="Get data rekening",
     * tags={"Rekening"},
     * security={{ "apiAuth": {} }},
     * @OA\Response(
     *  response=200,
     *  description="Data rekening berhasil ditemukan"
     * ),
     * )
     */
    public function index()
    {
        return response()->json([
            // 'total' => $total,
            'status' => 'success',
            'data' => $this->rekeningModel->all()
        ]);
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
        $result = $this->rekeningModel->create($request->all());
        return response()->json([
            // 'total' => $total,
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan',
            'data' => $result
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function total()
    {
        return response()->json([
            'status' => 'success',
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/master/rekening/{id}",
     * @OA\Parameter(
     *  name="id",
     *  description="ID rekening",
     *  required=true,
     *  in="path",
     * ),
     * summary="Get data rekening",
     * tags={"Rekening"},
     * security={{ "apiAuth": {} }},
     * @OA\Response(
     *  response=200,
     *  description="Data rekening berhasil ditemukan"
     * ),
     * )
     */
    public function show($id)
    {
        try {
            //code...
            return response()->json(
                [
                    'status' => 'success',
                    'data' => $this->rekeningModel->findOrFail($id)
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

            $response = $this->rekeningModel->findOrFail($id)->update($request->all());
            if (!$response) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'Data gagal diubah',
                        'data' => $this->rekeningModel->findOrFail($id)
                    ]
                );
            }
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data berhasil diubah'
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
                $mutasi = MutasiModel::where('master_rekening_id', $id)->get(); 
                ServisMutasiModel::whereIn('master_mutasi_id', $mutasi->pluck('id'))->delete();
                MutasiModel::whereIn('id', $mutasi->pluck('id'))->delete();

                $tagihan = TransaksiTagihanModel::where('master_rekening_id', $id)->get();
                TransaksiTagihanDetModel::whereIn('transaksi_tagihan_id', $tagihan->pluck('id'))->delete();
                TransaksiTagihanModel::whereIn('id', $tagihan->pluck('id'))->delete();
                $this->rekeningModel->findOrfail($id)->delete();
            }else{
                $this->rekeningModel->findOrfail($id)->delete();
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
