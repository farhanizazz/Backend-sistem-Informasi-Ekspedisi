<?php

namespace App\Http\Controllers\Api\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Requests\PengeluaranRequest\CreateRequest;
use App\Http\Requests\PengeluaranRequest\UpdateRequest;
use App\Models\Transaksi\PengeluaranModel;

class PengeluaranContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $pengeluaranModel;
    public function __construct()
    {
        $this->pengeluaranModel = new PengeluaranModel();
    }
    public function index()
    {
        $data = PengeluaranModel::with('master_armada:nopol')->get();

        return response()->json(
            [
                'status' => 'success',
                'data' => $data
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        //

        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $request->validator->errors()
                ]
            );
        }
        $this->pengeluaranModel->create($request->all());

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Data berhasil ditambahkan'
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
        //
        try {
            //code...
            return response()->json(
                [
                    'status' => 'success',
                    'data' => $this->pengeluaranModel->with('master_armada:nopol')->findOrFail($id)
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        //

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

            $response = $this->pengeluaranModel->findOrFail($id)->update($request->all());
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
    public function destroy($id)
    {
        //

        try {
            $this->pengeluaranModel->findOrfail($id)->delete();
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data berhasil dihapus'
                ]
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ]
            );
        }
    }
}
